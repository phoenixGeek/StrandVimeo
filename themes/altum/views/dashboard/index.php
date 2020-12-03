<?php defined('ALTUMCODE') || die() ?>

<section class="container">

    <?php display_notifications() ?>

    <div class="margin-top-3 d-flex justify-content-between">
        <h2 class="h4"><?= $this->language->dashboard->projects->header ?> ( <?= $data->count ?> )</h2>

        <div class="col-auto p-0">

                <button type="button" data-toggle="modal" data-target="#create_project" class="btn btn-primary rounded-pill"><i class="fa fa-plus-circle"></i> <?= $this->language->dashboard->projects->create ?></button>
        </div>
    </div>

    <?php if($data->projects_result->num_rows): ?>

        <?php while($row = $data->projects_result->fetch_object()): ?>
            <?php

            /* Get some stats about the project */
            $row->statistics = $this->database->query("SELECT COUNT(*) AS `total`, SUM(`clicks`) AS `clicks` FROM `links` WHERE `project_id` = {$row->project_id}")->fetch_object();

            ?>
            <div class="d-flex custom-row align-items-center my-4" data-project-id="<?= $row->project_id ?>">
                <div class="col-6">
                    <div class="font-weight-bold text-truncate h6">
                        <a href="<?= url('project/' . $row->project_id) ?>"><?= $row->name ?></a>
                    </div>

                </div>

                <div class="col-4 d-flex flex-column flex-lg-row justify-content-lg-around">
                    <div>
                        <span data-toggle="tooltip" title="<?= $this->language->project->links->total ?>" class="badge badge-info">
                            <i class="fa fa-fw fa-link mr-1"></i> <?= nr($row->statistics->total) ?>
                        </span>
                    </div>

                    <div>
                        <span data-toggle="tooltip" title="<?= $this->language->project->links->clicks ?>"class="badge badge-primary">
                            <i class="fa fa-fw fa-chart-bar mr-1"></i> <?= nr($row->statistics->clicks) ?>
                        </span>
                    </div>
                </div>

                <div class="col-2 d-flex justify-content-end">
                  

                    <a href="#" data-toggle="modal" data-target="#project_delete" data-project-id="<?= $row->project_id ?>" class="dropdown-item"><i class="fa fa-fw fa-times"></i> <?= $this->language->global->delete ?></a>
                   
                </div>
            </div>
        <?php endwhile ?>

    <?php else: ?>
        <div class="d-flex flex-column align-items-center justify-content-center">
            <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/no_data.svg' ?>" class="col-10 col-md-6 col-lg-4 mb-3" alt="<?= $this->language->dashboard->projects->no_data ?>" />
            <h2 class="h4 text-muted"><?= $this->language->dashboard->projects->no_data ?></h2>
            <p><a href="#" data-toggle="modal" data-target="#create_project"><?= $this->language->dashboard->projects->no_data_help ?></a></p>
        </div>
    <?php endif ?>

</section>

