<?php defined('ALTUMCODE') || die() ?>


<section class="container">

    <div class="margin-top-3 d-flex justify-content-between">
        <h2 class="h4"><?= $this->language->project->links->header ?> </h2>

        <div class="col-auto p-0">
            <?php if($this->settings->links->shortener_is_enabled): ?>
                <div class="dropdown">
                    <button type="button" data-toggle="dropdown" class="btn btn-primary rounded-pill dropdown-toggle dropdown-toggle-simple">
                        <i class="fa fa-plus-circle"></i> <?= $this->language->project->links->create ?>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="#" class="dropdown-item" data-toggle="modal" data-target="#create_biolink">
                            <i class="fa fa-fw fa-circle fa-sm mr-1" style="color: <?= $this->language->link->biolink->color ?>"></i>

                            <?= $this->language->link->biolink->name ?>
                        </a>


                    </div>
                </div>
            <?php else: ?>
                <button type="button" data-toggle="modal" data-target="#create_biolink" class="btn btn-primary rounded-pill">
                    <i class="fa fa-plus-circle"></i> <?= $this->language->project->links->create ?>
                </button>
            <?php endif ?>
        </div>
    </div>

    <?php if(count($data->links_logs)): ?>

        <?php foreach($data->links_logs as $row): ?>

        <div class="d-flex custom-row align-items-center my-4 <?= $row->is_enabled ? null : 'custom-row-inactive' ?>">

            <div class="col-1 p-0">

                <span class="fa-stack fa-1x" data-toggle="tooltip" title="<?= $this->language->link->{$row->type}->name ?>">
                  <i class="fa fa-circle fa-stack-2x" style="color: <?= $this->language->link->{$row->type}->color ?>"></i>
                  <i class="fas <?= $this->language->link->{$row->type}->icon ?> fa-stack-1x fa-inverse"></i>
                </span>

            </div>

            <div class="col-8 col-md-5">
                <div class="d-flex flex-column">
           
                    <a href="<?= url('link/' . $row->link_id) ?>" class="font-weight-bold"><?= $row->url ?></a>
                    <span class="d-flex align-items-center">
                        <span class="d-inline-block text-truncate">
                        <?php if(!empty($row->location_url)): ?>
                            <img src="https://www.google.com/s2/favicons?domain=<?= parse_url($row->location_url)['host'] ?>" class="img-fluid mr-1" />
                            <a href="<?= $row->location_url ?>" class="text-muted align-middle"><?= $row->location_url ?></a>
                        <?php else: ?>
                            <img src="https://www.google.com/s2/favicons?domain=<?= $row->full_url ?>" class="img-fluid mr-1" />
                            <a href="<?= $row->full_url ?>" class="text-muted align-middle"><?= $row->full_url ?></a>
                        <?php endif ?>
                        </span>
                    </span>
                </div>
            </div>

            <div class="col-2 d-flex justify-content-end col-md-auto">
                <div class="dropdown">
                    <a href="#" data-toggle="dropdown" class="text-secondary dropdown-toggle dropdown-toggle-simple">
                        <i class="fa fa-ellipsis-v"></i>

                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="<?= url('link/' . $row->link_id) ?>" class="dropdown-item"><i class="fa fa-fw fa-pencil-alt"></i> <?= $this->language->global->edit ?></a>
  
                            <a href="#" class="dropdown-item" data-delete="<?= $this->language->global->info_message->confirm_delete ?>" data-row-id="<?= $row->link_id ?>"><i class="fa fa-fw fa-times"></i> <?= $this->language->global->delete ?></a>

                        </div>
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach ?>

    <?php else: ?>

        <div class="d-flex flex-column align-items-center justify-content-center">
            <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/no_data.svg' ?>" class="col-10 col-md-6 col-lg-4 mb-3" alt="<?= $this->language->project->links->no_data ?>" />
            <h2 class="h4 text-muted"><?= $this->language->project->links->no_data ?></h2>
            <p><a href="#" data-toggle="modal" data-target="#create_biolink"><?= $this->language->project->links->no_data_help ?></a></p>
        </div>

    <?php endif ?>

</section>

<?php ob_start() ?>
<script src="<?= SITE_URL . ASSETS_URL_PATH . 'js/libraries/Chart.bundle.min.js' ?>"></script>
<script>

    /* Delete handler */
    $('[data-delete]').on('click', event => {
        let message = $(event.currentTarget).attr('data-delete');

        if(!confirm(message)) return false;

        /* Continue with the deletion */
        ajax_call_helper(event, 'link-ajax', 'delete', () => {

            /* On success delete the actual row from the DOM */
            $(event.currentTarget).closest('.custom-row').remove();

        });

    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
