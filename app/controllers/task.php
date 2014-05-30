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
    }

    public function index_action($portfolio_id)
    {
        $this->portfolio = \Portfolio\Portfolios::find($portfolio_id);

        if (!$this->portfolio) {
            $this->redirect('portfolio');
        }

        // get tags
        foreach ($this->portfolio->tasks as $task) {
            $tags = $task->tags->pluck('tag');

            if (empty($tags)) {
                $this->tagless_tasks[] = $task;
            } else {
                // collect all tag-combinations and group tasks by tags
                foreach ($tags as $tag) {
                    $this->tasks_by_tag[$tag][] = $task;

                    foreach ($tags as $tag2) {
                        if ($tag != $tag2) {
                            $this->tags[$tag][] = $tag2;
                        }
                    }
                }
            }
        }
    }

    public function new_action($portfolio_id)
    {
        $this->portfolios = Portfolio\Portfolios::getPortfoliosForUser($this->container['user']->id);

        $this->tags = Portfolio\Tags::findByUser_id($this->container['user']->id);

        $this->portfolio_id = $portfolio_id;
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

        $task = Portfolio\Tasks::create($data);

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

            $task->perms[] = $p;
        }

        $task->store();

        $this->redirect('task/index/' . $portfolio_id);
    }

    public function edit_action($portfolio_id, $task_id)
    {
        $this->portfolios = Portfolio\Portfolios::getPortfoliosForUser($this->container['user']->id);

        $this->portfolio_id = $portfolio_id;
        $this->task = Portfolio\Tasks::find($task_id);
        $this->tags = Portfolio\Tags::findBySQL('user_id = ? ORDER BY tag ASC', array($this->container['user']->id));

        $this->task_tags = $this->task->tags->pluck('tag');
        $this->task_portfolios = $this->task->portfolios->pluck('id');
    }
    
    public function update_action($portfolio_id, $task_id)
    {
        $user_id = $this->container['user']->id;

        // update task contents
        $task = Portfolio\Tasks::find($task_id);
        $task->setData(array(
            'title'       => Request::get('title'),
            'content'     => Request::get('content')
        ));

        // update sets
        $task->portfolios = array();

        // add the task to the correct portfolio
        foreach (Request::getArray('sets') as $id) {
            $task->portfolios[] = Portfolio\Portfolios::find($id);
        }

        
        // update tags
        $diff = Portfolio\Helper::pick($task->tags->pluck('tag'), Request::getArray('tags'));

        foreach ($diff['deleted'] as $del_tag) {
            foreach ($task->tags as $key => $tag) {
                if ($del_tag == $tag['tag']) {
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

        $task->store();

        $this->redirect('task/index/' . $portfolio_id);
    }

    public function delete_action($portfolio_id, $task_id)
    {
        $this->redirect('task/index/' . $portfolio_id);
    }
}
