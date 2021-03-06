<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Controller;

use Hiject\Model\SubtaskModel;

/**
 * Subtask Restriction
 */
class SubtaskRestrictionController extends BaseController
{
    /**
     * Show popup
     *
     * @access public
     */
    public function show()
    {
        $task = $this->getTask();
        $subtask = $this->getSubtask();

        $this->response->html($this->template->render('subtask_restriction/show', array(
            'status_list' => array(
                SubtaskModel::STATUS_TODO => t('Todo'),
                SubtaskModel::STATUS_DONE => t('Done'),
            ),
            'subtask_inprogress' => $this->subtaskStatusModel->getSubtaskInProgress($this->userSession->getId()),
            'subtask' => $subtask,
            'task' => $task,
        )));
    }

    /**
     * Change status of the in progress subtask and the other subtask
     *
     * @access public
     */
    public function save()
    {
        $task = $this->getTask();
        $subtask = $this->getSubtask();
        $values = $this->request->getValues();

        // Change status of the previous "in progress" subtask
        $this->subtaskModel->update(array(
            'id' => $values['id'],
            'status' => $values['status'],
        ));

        // Set the current subtask to "in progress"
        $this->subtaskModel->update(array(
            'id' => $subtask['id'],
            'status' => SubtaskModel::STATUS_INPROGRESS,
        ));

        $this->response->redirect($this->helper->url->to('TaskViewController', 'show', array('project_id' => $task['project_id'], 'task_id' => $task['id'])), true);
    }
}
