<div class="filter-box margin-bottom">
    <form method="get" action="<?= $this->url->dir() ?>" class="search">
        <?= $this->form->hidden('controller', array('controller' => 'SearchController')) ?>
        <?= $this->form->hidden('action', array('action' => 'index')) ?>

        <div class="input-addon">
            <?= $this->form->text('search', array(), array(), array('placeholder="'.t('Search').'"'), 'input-addon-field') ?>
            <div class="input-addon-item">
                <?= $this->render('app/filters_helper') ?>
            </div>
        </div>
    </form>
</div>

<?php if (empty($overview_paginator)): ?>
    <p class="alert"><?= t('There is nothing assigned to you.') ?></p>
<?php else: ?>
    <div class="<?php echo !$this->app->configHelper->get('show_activity_list') ? '' : 'dashboard-content'; ?>">
      <?php foreach ($overview_paginator as $result): ?>
          <?php if (! $result['paginator']->isEmpty()): ?>
              <div class="page-header">
                  <h2 id="project-tasks-<?= $result['project_id'] ?>"><?= $this->url->link($this->text->e($result['project_name']), 'BoardViewController', 'show', array('project_id' => $result['project_id'])) ?></h2>
              </div>

              <div class="table-list">
                  <?= $this->render('task_list/header', array(
                      'paginator' => $result['paginator'],
                  )) ?>

                  <?php foreach ($result['paginator']->getCollection() as $task): ?>
                      <div class="table-list-row color-<?= $task['color_id'] ?>">
                          <?= $this->render('task_list/task_title', array(
                              'task' => $task,
                              'redirect' => 'dashboard',
                          )) ?>

                          <?= $this->render('task_list/task_details', array(
                              'task' => $task,
                          )) ?>

                          <?= $this->render('task_list/task_avatars', array(
                              'task' => $task,
                          )) ?>

                          <?= $this->render('task_list/task_icons', array(
                              'task' => $task,
                          )) ?>

                          <?= $this->render('task_list/task_subtasks', array(
                              'task'    => $task,
                              'user_id' => $user['id'],
                          )) ?>

                          <?= $this->hook->render('template:dashboard:task:footer', array('task' => $task)) ?>
                      </div>
                  <?php endforeach ?>
              </div>

              <?= $result['paginator'] ?>
          <?php endif ?>
      <?php endforeach ?>
    </div>

    <?php if (!!$this->app->configHelper->get('show_activity_list')): ?>
    <div class="dashboard-activity">
      <?php
        //
        // TODO: move this to a controller`
        //
        $projects = [];
        foreach ($project_paginator->getCollection() as $project) {
          $projects[] = $project['id'];
        }

        $events = $this->helper->projectActivity->getProjectsEvents($projects, $this->app->configHelper->get('activity_items'));

        echo $this->render('kanext:event/dashevents', array('events' => $events));
      ?>
    </div>
    <?php endif; ?>

<?php endif ?>
