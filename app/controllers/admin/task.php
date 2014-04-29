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
class Admin_TaskController extends PortfolioPluginController
{

    public function before_filter(&$action, &$args)
    {
        parent::before_filter($action, $args);

        Portfolio\Perm::check('admin');
        
        Navigation::activateItem('/admin/config/portfolio');
    }
    
    public function index_action($portfolio_id)
    {
        SimpleORMap::expireTableScheme();
        
        $this->portfolio = Portfolio\Tasksets::find($portfolio_id);
        
        if (!$this->portfolio) {
            $this->redirect('admin/set/index');
        }
    }
    
    public function new_action($portfolio_id)
    {
        $this->portfolio_id = $portfolio_id;
        $this->portfolios   = Portfolio\Tasksets::findBySQL('1 ORDER BY name DESC');
        $this->tags         = Portfolio\Tags::findBySQL('user_id = ? ORDER BY tag ASC', array($GLOBALS['user']->id));
    }
    
    public function add_action($portfolio_id)
    {
        SimpleORMap::expireTableScheme();

        $user_id = $this->container['user']->id;

        $data = array(
            'user_id'     => 'global',
            'title'       => Request::get('title'),
            'content'     => Request::get('content'),
            'allow_text'  => Request::option('allow_text') ? 1 : 0,
            'allow_files' => Request::option('allow_files') ? 1 : 0
        );

        $task = Portfolio\Tasks::create($data);

        foreach (Request::optionArray('sets') as $pid) {
            $taskset_combo = Portfolio\Tasksets::find($pid);
            $task->tasksets[] = $taskset_combo;
        }

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
        
        $task->store();
        
        $this->redirect('admin/task/index/' . $portfolio_id);
    }
    
    public function update_action($portfolio_id, $task_id)
    {
        $task = Portfolio\Tasks::find($task_id);
        $task->setData(array(
            'user_id'     => 'global',
            'title'       => Request::get('title'),
            'content'     => Request::get('content'),
            'allow_text'  => Request::option('allow_text') ? 1 : 0,
            'allow_files' => Request::option('allow_files') ? 1 : 0
        ));
        
        /*
       foreach (Request::optionArray('sets') as $pid) {
            $taskset_combo = Portfolio\Tasksets::find($pid);
            $task->tasksets[] = $taskset_combo;
        }
         * 
         */

        $tags = $task->tags->pluck('tag');

        Portfolio\Helper::pick($task->tags, Request::getArray('tags'));

        die;
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
        
        die;

        $task->store();

        /*
        $this->portfolio_id = $portfolio_id;
        $this->portfolios   = Portfolio\Tasksets::findBySQL('1 ORDER BY name DESC');
        $this->tags         = Portfolio\Tags::findBySQL('user_id = ? ORDER BY tag ASC', array($GLOBALS['user']->id));
         * 
         */
        
        $this->redirect('admin/task/index/' . $portfolio_id);
    }

    public function edit_action($portfolio_id, $task_id)
    {
        $this->task = Portfolio\Tasks::find($task_id);

        $this->portfolio_id = $portfolio_id;
        $this->portfolios   = Portfolio\Tasksets::findBySQL('1 ORDER BY name DESC');
        $this->tags         = Portfolio\Tags::findBySQL('user_id = ? ORDER BY tag ASC', array($GLOBALS['user']->id));
    }
    
    public function delete_action($portfolio_id, $task_id)
    {
        $taskset = Portfolio\Tasks::find($task_id);
        $taskset->delete();

        $this->redirect('admin/task/index/' . $portfolio_id);
    }
}