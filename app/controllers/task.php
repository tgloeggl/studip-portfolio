<?php
/**
 * TaskController - Short description for file
 *
 * Long description for file (if any)...
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 3 of
 * the License, or (at your option) any later version.
 *
 * @author      Till Glöggler <tgloeggl@uos.de>
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GPL version 3
 * @category    Stud.IP
 */
class TaskController extends PortfolioPluginController
{
    function before_filter(&$action, &$args)
    {
        parent::before_filter($action, $args);

        Navigation::activateItem('/profile/portfolio');

        $this->user = $this->container['user'];
        $this->set_layout('layout');
    }

    public function index_action($portfolio_id)
    {
        $this->portfolio = \Portfolio\Portfolios::find($portfolio_id);

        if (!$this->portfolio) {
            $this->redirect('portfolio');
        }

        foreach (Portfolio\Helper::sortTasksByTags($this->portfolio->tasks) as $key => $data) {
            $this->$key = $data;
        }
    }

    public function new_action($portfolio_id)
    {
        $this->portfolio = \Portfolio\Portfolios::find($portfolio_id);
        $this->portfolios = Portfolio\Portfolios::getPortfoliosForUser($this->container['user']->id);

        $this->tags = Portfolio\Tags::findByUser_id($this->container['user']->id);
    }

    public function add_action($portfolio_id)
    {
        $user_id = $this->container['user']->id;

        $data = array(
            'user_id'     => $user_id,
            'title'       => Request::get('title'),
            'content'     => Request::get('content'),
            'allow_text'  => 1,
            'allow_files' => 1
        );

        $task      = Portfolio\Tasks::create($data);
        $task_user = Portfolio\TaskUsers::create(array(
            'user_id'            => $user_id,
            'portfolio_tasks_id' => $task->id
        ));

        // add the task to the correct portfolio
        foreach (Request::getArray('sets') as $id) {
            $task->portfolios[] = Portfolio\Portfolios::find($id);
        }

        // set the tags for the task
        foreach (Request::getArray('tags') as $tag_name) {
            if (!$tag = current(Portfolio\Tags::findBySQL('user_id = ? AND tag = ?', array($user_id, $tag_name)))) {
                $data = array(
                    'user_id' => $user_id,
                    'tag'     => $tag_name
                );
                $tag = Portfolio\Tags::create($data);
            }

            $task->tags[] = $tag;
        }

        // set the permissions for the task
        foreach (Request::getArray('perms') as $username => $perm) {
            $p = new Portfolio\Permissions();
            $p->setData(array(
                'user_id' => get_userid($username),
                'role'    => $perm
            ));

            $task_user->perms[] = $p;
        }

        $task->store();

        $this->redirect('task/index/' . $portfolio_id);
    }

    public function edit_action($portfolio_id, $task_id, $task_user_id = null)
    {
        $user_id = $this->container['user']->id;

        if ($portfolio_id == 0) {
            $this->portfolio->id = 0;
        } else {
            $this->portfolio = \Portfolio\Portfolios::find($portfolio_id);
        }

        $this->task = Portfolio\Tasks::find($task_id);

        $this->task_tags = $this->task->tags->pluck('tag');
        $this->task_portfolios = $this->task->portfolios->pluck('id');

        // make sure we have an user-entry for the current task
        if ($task_user_id) {
            $this->task_user = Portfolio\TaskUsers::find($task_user_id);
        } else {
            if (!$this->task_user = current(Portfolio\TaskUsers::findBySQL('user_id = ? AND portfolio_tasks_id = ?', array($user_id, $task_id)))) {
                $this->task_user = Portfolio\TaskUsers::create(array(
                    'user_id'            => $user_id,
                    'portfolio_tasks_id' => $task_id
                ));
            }
        }

        $this->perms = Portfolio\Perm::get($user_id, $this->task_user);

        // this stuff is only available, if the user is owner of the current task(_user)
        if ($this->task_user->user_id == $user_id) {
            $this->tags = Portfolio\Tags::findBySQL('user_id = ? ORDER BY tag ASC', array($user_id));
            $this->portfolios = Portfolio\Portfolios::getPortfoliosForUser($user_id);
        }
    }

    public function update_action($portfolio_id, $task_user_id)
    {
        $user_id = $this->container['user']->id;

        // update task contents
        $task_user = Portfolio\TaskUsers::find($task_user_id);
        $task      = $task_user->task;

        $perms = Portfolio\Perm::get($user_id, $task_user);

        if ($perms['edit_task']) {
            $task->setData(array(
                'title'       => Request::get('title'),
                'content'     => Request::get('content')
            ));
        }


        if ($perms['close_task']) {
            $task_user->closed = Request::option('close') ? 1 : 0;
        }

        if ($perms['edit_settings']) {
            // update sets
            foreach ($task->portfolios as $key => $portfolio) {
                if (!$portfolio->global) {
                    unset($task->portfolios[$key]);
                }
            }

            // add the task to the correct portfolio
            foreach (Request::getArray('sets') as $id) {
                $task->portfolios[] = Portfolio\Portfolios::find($id);
            }


            // update tags
            $diff = Portfolio\Helper::pick($task->tags->pluck('tag'), array_unique(Request::getArray('tags')));

            foreach ($diff['deleted'] as $del_tag) {
                foreach ($task->tags as $key => $tag) {
                    if ($del_tag == $tag['tag'] && $tag['user_id'] != 'global') {
                        unset($task->tags[$key]);
                    }
                }
            }

            foreach ($diff['added'] as $tag_name) {
                if (!$tag = current(Portfolio\Tags::findBySQL('user_id = ? AND tag = ?', array($user_id, $tag_name)))) {
                    $data = array(
                        'user_id' => $user_id,
                        'tag'     => $tag_name
                    );
                    $tag = Portfolio\Tags::create($data);
                }
                $task->tags[] = $tag;
            }

            // update the permissions for the task
            foreach (Request::getArray('perms') as $username => $perm) {
                // only add permissions for users other than the current one (the owner)
                if ($username != $this->container['user']->username) {
                    $new_perms[] = array(get_userid($username), $perm);
                }
            }

            $diff = Portfolio\Helper::pick($task_user->perms->pluck('user_id role'), $new_perms);

            foreach ($diff['deleted'] as $del) {
                foreach ($task_user->perms as $key => $perm) {
                    if ($perm->user_id == $del[0] && $perm->role == $del[1]) {
                        unset($task_user->perms[$key]);
                    }
                }
            }

            // store to delete old ones before trying to add new ones
            $task_user->store();

            foreach ($diff['added'] as $add) {
                $perm = new Portfolio\Permissions();
                $perm->setData(array(
                    'user_id' => $add[0],
                    'role'    => $add[1]
                ));
                $task_user->perms[] = $perm;
            }

            $task->store();
        }

        $data = Request::getArray('task_user');

        if ($perms['edit_answer']) {
            $task_user->answer = $data['answer'];
        }

        if ($perms['edit_feedback']) {
            $task_user->feedback = $data['feedback'];
        }

        if ($perms['edit_goal']) {
            $task_user->goal = $data['goal'];
        }

        $task_user->store();

        $this->redirect('task/index/' . $portfolio_id);
    }

    public function delete_action($portfolio_id, $task_id)
    {
        $task = Portfolio\Tasks::find($task_id);

        if ($task->user_id == $this->container['user']->id) {
            $task->delete();
        }

        $this->redirect('task/index/' . $portfolio_id);
    }
}
