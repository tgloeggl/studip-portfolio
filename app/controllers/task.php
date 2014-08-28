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

        $this->permissions = array(
            'tutor'          => _('Betreuer/in'),
            'student'        => _('Kommilitone/in'),
            'followup-tutor' => _('Nachfolgebetreuer/in')
        );
    }

    public function index_action($portfolio_id)
    {
        $this->portfolio = \Portfolio\Portfolios::find($portfolio_id);

        if (!$this->portfolio) {
            $this->redirect('portfolio');
            return;
        }

        foreach (Portfolio\Helper::sortTasksByTags($this->portfolio->tasks->filter(function($entry) {
                if (in_array($entry->user_id, words('global ' . $this->user->id)) !== false) {
                    return $entry;
                }
            })) as $key => $data) {
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

    /**
     * update an user-instance of a task
     *
     * @param int $portfolio_id
     * @param int $task_user_id
     */
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
        }

        $data = Request::getArray('task_user');

        if ($perms['edit_answer']) {
            $task_user->answer = $data['answer'];
        }

        if ($perms['edit_feedback']) {
            $task_user->feedback->feedback = $data['feedback'];
            $task_user->feedback->user_id  = $user_id;
        }

        if ($perms['edit_goal']) {
            $task_user->goal = $data['goal'];
        }

        $task_user->store();

        $this->flash['messages'] = array(
            array('type' => 'success', 'text' => _('Die Aufgabe wurde gespeichert'))
        );

        $this->redirect('task/edit/' . $portfolio_id .'/'. $task->id .'/'. $task_user_id);
    }

    /**
     * add a permission for an user-instance of a task
     *
     * @param int $task_user_id
     */
    function add_permission_action($task_user_id)
    {
        $this->render_nothing();

        $task_user = Portfolio\TaskUsers::find($task_user_id);

        $perm = new Portfolio\Permissions();

        $user_id = get_userid(Request::get('user'));

        // the user ist not allowed to store a perm for himself
        if ($user_id == $this->container['user']->id) {
            $this->response->set_status(400, _('Sie dürfen sich nicht selbst für eine Berechtigung eintragen!'));
            return;
        }

        // check that the submitted user has not another perm already
        foreach ($task_user->perms as $key => $perm) {
            if ($perm->user_id == $user_id) {
                $this->response->set_status(400, _('Für diesen Nutzer existiert bereits eine andere Berechtigung!'));
            }
            return;
        }

        // add new permission entry
        $perm->setData(array(
            'user_id' => $user_id,
            'role'    => Request::option('perm')
        ));

        $task_user->perms[] = $perm;

        $task_user->store();
    }

    /**
     * delete a permission for an user-instance of a task
     *
     * @param int $task_user_id
     */
    function delete_permission_action($task_user_id)
    {
        $task_user = Portfolio\TaskUsers::find($task_user_id);

        $user_id = get_userid(Request::get('user'));

        foreach ($task_user->perms as $key => $perm) {
            if ($perm->user_id == $user_id) {
                unset($task_user->perms[$key]);
            }
        }

        $task_user->store();

        $this->render_nothing();
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
