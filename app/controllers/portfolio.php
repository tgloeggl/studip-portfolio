<?php
/**
 * PortfolioController - Short description for file
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
class PortfolioController extends PortfolioPluginController
{

    public function index_action()
    {
        Navigation::activateItem('/profile/portfolio');

        $this->set_layout('layout');

        $this->portfolios = Portfolio\Portfolios::getPortfoliosForUser($this->container['user']->id);

        $this->task_users = Portfolio\Helper::getForeignTasksForUser($this->container['user']->id);

        $this->portfolio->id = 0;

        foreach (Portfolio\Helper::sortTaskUsersByTags($this->task_users) as $key => $data) {
            $this->$key = $data;
        }
    }

    public function add_action($name)
    {
        if (strlen(trim($name)) == 0) {
            $name = _('Neues Portfolio');
        }

        Portfolio\Portfolios::create(array(
            'name'    => $name,
            'user_id' => $this->container['user']->id
        ));

       $this->redirect('portfolio/index');
    }

    public function update_action($id)
    {
        if (strlen(trim($name = Request::get('name'))) == 0) {
            $name = _('Neues Portfolio');
        }

        $portfolio = Portfolio\Portfolios::find($id);
        $portfolio->name = $name;
        $portfolio->store();

        $this->render_nothing();
    }

    public function delete_action($portfolio_id)
    {
        $portfolio = Portfolio\Portfolios::find($portfolio_id);

        if ($portfolio->user_id == $this->container['user']->id) {
            $portfolio->delete();
        }

        $this->redirect('portfolio/index');
    }
}
