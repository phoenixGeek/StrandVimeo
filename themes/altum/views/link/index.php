<?php defined('ALTUMCODE') || die() ?>

<input type="hidden" id="base_controller_url" name="base_controller_url" value="<?= url('link/' . $data->link->link_id) ?>" />
<input type="hidden" name="link_base" value="<?= $this->link->domain ? $this->link->domain->url : url() ?>" />

<header class="header">
    <div class="container">

        <nav aria-label="breadcrumb">
            <small>
                <ol class="custom-breadcrumbs">
                    <li><a href="<?= url('dashboard') ?>"><?= $this->language->dashboard->breadcrumb ?></a> <i class="fa fa-fw fa-angle-right"></i></li>
                    <li><a href="<?= url('project/' . $data->link->project_id) ?>"><?= $this->language->project->breadcrumb ?></a> <i class="fa fa-fw fa-angle-right"></i></li>
                    <li>
                        <?php if($data->link->type == 'biolink'): ?>
                            <a href="<?= url('link/' . $data->link->link_id) ?>"><?= $this->language->link->breadcrumb_biolink ?></a> <i class="fa fa-fw fa-angle-right"></i>
                        <?php elseif($data->link->type == 'link'): ?>
                            <a href="<?= url('link/' . $data->link->link_id) ?>"><?= $this->language->link->breadcrumb_link ?></a> <i class="fa fa-fw fa-angle-right"></i>
                        <?php endif ?>
                    </li>

                    <?php if($data->link->type == 'biolink' && $data->link->subtype != 'base'): ?>
                        <li><?= $this->language->link->breadcrumb_link ?> <i class="fa fa-fw fa-angle-right"></i></li>
                    <?php endif ?>

                    <?php if($data->method == 'settings'): ?>
                        <li class="active" aria-current="page"><?= $this->language->link->settings->breadcrumb ?></li>
                    <?php elseif($data->method == 'statistics'): ?>
                        <li class="active" aria-current="page"><?= $this->language->link->statistics->breadcrumb ?></li>
                    <?php endif ?>
                </ol>
            </small>
        </nav>

        <div class="d-flex flex-column flex-md-row justify-content-between">
            <div class="d-flex align-items-center">
                <?php if(!$data->link->active): ?>
                    <h1 id="link_url" class="h3 mr-3"><?= sprintf($this->language->link->header->header, $data->link->templink) ?></h1>
                <?php else: ?>
                    <h1 id="link_url" class="h3 mr-3"><?= sprintf($this->language->link->header->header, $data->link->url) ?></h1>
                <?php endif ?>

                <div class="custom-control custom-switch mr-3" data-toggle="tooltip" title="<?= $this->language->project->links->is_enabled_tooltip ?>">
                    <input
                            type="checkbox"
                            class="custom-control-input"
                            id="link_is_enabled_<?= $data->link->link_id ?>"
                            data-row-id="<?= $data->link->link_id ?>"
                            onchange="ajax_call_helper(event, 'link-ajax', 'is_enabled_toggle')"
                        <?= $data->link->is_enabled ? 'checked="true"' : null ?>
                    >
                    <label class="custom-control-label clickable" for="link_is_enabled_<?= $data->link->link_id ?>"></label>
                </div>

                <div class="dropdown">
                    <a href="#" data-toggle="dropdown" class="text-secondary dropdown-toggle dropdown-toggle-simple">
                        <i class="fa fa-ellipsis-v"></i>

                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="<?= url('link/' . $data->link->link_id) ?>" class="dropdown-item"><i class="fa fa-fw fa-pencil-alt"></i> <?= $this->language->global->edit ?></a>

                            <a href="<?= $data->link->full_url . '/qr' ?>" target="_blank" class="dropdown-item"><i class="fa fa-fw fa-qrcode"></i> <?= $this->language->link->qr->link ?></a>
                            <a href="#" class="dropdown-item" data-delete="<?= $this->language->global->info_message->confirm_delete ?>" data-row-id="<?= $data->link->link_id ?>"><i class="fa fa-fw fa-times"></i> <?= $this->language->global->delete ?></a>
                        </div>
                    </a>
                </div>
            </div>

            <div class="d-none d-md-block">
                <?php if(($data->link->type == 'biolink' && $data->link->subtype == 'base') || $data->link->type == 'link'): ?>


                    <?php if($data->method != 'settings'): ?>
                        <a href="<?= url('link/' . $data->link->link_id . '/settings') ?>" class="btn btn-secondary rounded-pill mr-3"><i class="fa fa-fw fa-cogs"></i> <?= $this->language->link->settings->link ?></a>
                    <?php endif ?>
                <?php endif ?>
            </div>
        </div>

        
        <div class="d-flex align-items-baseline">
                    
            <span class="mr-3" data-toggle="tooltip" title="<?= $this->language->link->{$data->link->type}->name ?>">
                <i class="fa fa-fw fa-circle fa-sm" style="display: <?= !$data->link->active? 'none;': 'block;' ?> color: <?= $this->language->link->{$data->link->type}->color ?>"></i>
            </span>
            <div class="col-8 col-md-auto text-muted text-truncate" style="display: <?= !$data->link->active? 'none;': 'block;' ?>">
                <?= sprintf($this->language->link->header->subheader, '<a id="link_full_url" href="' . $data->link->full_url . '" target="_blank">' . $data->link->full_url . '</a>') ?>

            </div>

            <button 
                style="display: <?= !$data->link->active? 'none;': 'block;' ?>"
                id="link_full_url_copy"
                type="button"
                class="btn btn-link"
                data-toggle="tooltip"
                title="<?= $this->language->global->clipboard_copy ?>"
                aria-label="<?= $this->language->global->clipboard_copy ?>"
                data-clipboard-text="<?= $data->link->full_url ?>"
            >
            <i class="fa fa-fw fa-sm fa-copy"></i>
            </button>

        </div>
        
    </div>
</header>

<section class="container">

    <?php display_notifications() ?>
    
    <?= $this->views['method'] ?>

</section>

<?php ob_start() ?>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<link href="<?= SITE_URL . ASSETS_URL_PATH . 'css/pickr.min.css' ?>" rel="stylesheet" media="screen">
<link href="<?= SITE_URL . ASSETS_URL_PATH . 'css/datepicker.min.css' ?>" rel="stylesheet" media="screen">
<link href="<?= SITE_URL . ASSETS_URL_PATH . 'css/bootstrap-iconpicker.min.css' ?>" rel="stylesheet" media="screen">

<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

<?php ob_start() ?>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<script>

    let clipboard = new ClipboardJS('[data-clipboard-text]');

    /* Delete handler for the notification */
    $('[data-delete]').on('click', event => {

        let message = $(event.currentTarget).attr('data-delete');
        if(!confirm(message)) return false;

        /* Continue with the deletion */
        ajax_call_helper(event, 'link-ajax', 'delete', (event, data) => {

            fade_out_redirect({ url: data.details.url, full: true });
        });

    });


    $("#link_full_url_copy").on('click', event => {
        
        toastr.success('Linkinbio Url is copied to clipboard');
    })
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
