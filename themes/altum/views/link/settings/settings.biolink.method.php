<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>

<div class="row">
    <div class="col-12 col-lg-7">

        <div class="d-flex justify-content-between">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link <?= !isset($_GET['tab']) || (isset($_GET['tab']) && $_GET['tab'] == 'settings') ? 'active' : null ?>" id="settings-tab" data-toggle="pill" href="#settings" role="tab" aria-controls="settings" aria-selected="true"><?= $this->language->link->header->settings_tab ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= isset($_GET['tab']) && $_GET['tab'] == 'links'? 'active' : null ?>" id="links-tab" data-toggle="pill" href="#links" role="tab" aria-controls="links" aria-selected="false"><?= $this->language->link->header->links_tab ?></a>
                </li>
            </ul>


                        
            <div class="dropdown">
                <button type="button" data-toggle="dropdown" class="btn btn-primary rounded-pill dropdown-toggle dropdown-toggle-simple"><i class="fa fa-fw fa-plus-circle"></i> <?= $this->language->project->links->create ?></button>

                <div class="dropdown-menu dropdown-menu-right">
                    <?php $biolink_link_types = require APP_PATH . 'includes/biolink_link_types_list.php'; ?>

                    <?php foreach($biolink_link_types as $key): ?>
                    <a href="#" class="dropdown-item" data-toggle="modal" data-target="#create_biolink_<?= $key ?>">
                        <i class="fa fa-fw fa-circle fa-sm mr-1" style="color: <?= $this->language->link->biolink->{$key}->color ?>"></i>

                        <?= $this->language->link->biolink->{$key}->name ?>
                    </a>
                    <?php endforeach ?>
                </div>
            </div>
                
        </div>

        <div class="tab-content">
            <div class="tab-pane fade <?= !isset($_GET['tab']) || (isset($_GET['tab']) && $_GET['tab'] == 'settings') ? 'show active' : null ?>" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                <div class="card border-0">
                    <div class="card-body">

                        <form name="update_biolink" action="" method="post" role="form" enctype="multipart/form-data">
                            <input type="hidden" name="request_type" value="update" />
                            <input type="hidden" name="type" value="biolink" />
                            <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />

                            <div class="notification-container"></div>

                            <div class="form-group" style="display: <?= $data->link->active? 'block;' :'none;' ?>">
                                <label><i class="fa fa-fw fa-link fa-sm mr-1"></i> <?= $this->language->link->settings->url ?></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">

                                            <span class="input-group-text"><?= url() ?></span>

                                    </div>

                                    <input type="text" class="form-control" name="url" placeholder="<?= $this->language->link->settings->url_placeholder ?>" value="<?= $data->link->url ?>" />
                                </div>
                                <small class="text-muted"><?= $this->language->link->settings->url_help ?></small>
                            </div>


                            <div class="form-group">
                                <label for="settings_title"><i class="fa fa-fw fa-heading fa-sm mr-1"></i> <?= $this->language->link->settings->title ?></label>
                                <input type="text" id="settings_title" name="title" class="form-control" value="<?= $data->link->settings->title ?>" required="required" />
                            </div>

                            <div class="form-group">
                                <label for="settings_description"><i class="fa fa-fw fa-pen-fancy fa-sm mr-1"></i> <?= $this->language->link->settings->description ?></label>
                                <input type="text" id="settings_description" name="description" class="form-control" value="<?= $data->link->settings->description ?>" />
                            </div>


                        </form>

                    </div>
                </div>
            </div>
            <div class="tab-pane fade <?= isset($_GET['tab']) && $_GET['tab'] == 'links'? 'show active' : null ?>" id="links" role="tabpanel" aria-labelledby="links-tab">

                <?php if($data->link_links_result->num_rows): ?>
                    <?php while($row = $data->link_links_result->fetch_object()): ?>
                    <?php $row->settings = json_decode($row->settings) ?>

                        <div class="link card border-0 <?= $row->is_enabled ? null : 'custom-row-inactive' ?> my-4" data-link-id="<?= $row->link_id ?>">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="custom-row-side-controller">
                                        <span data-toggle="tooltip" title="<?= $this->language->link->links->link_sort ?>">
                                            <i class="fa fa-fw fa-bars text-muted custom-row-side-controller-grab drag"></i>
                                        </span>
                                    </div>

                                    <div class="col-1 mr-2 p-0 d-none d-lg-block">

                                        <span class="fa-stack fa-1x" data-toggle="tooltip" title="<?= $this->language->link->biolink->{$row->subtype}->name ?>">
                                            <i class="fa fa-circle fa-stack-2x" style="color: <?= $this->language->link->biolink->{$row->subtype}->color ?>"></i>
                                            <i class="fas <?= $this->language->link->biolink->{$row->subtype}->icon ?> fa-stack-1x fa-inverse"></i>
                                        </span>

                                    </div>
 
                                    <div class="col-7 col-md-8">
                                        <div class="d-flex flex-column">
                                            <a  href="#"
                                                data-toggle="collapse"
                                                data-target="#link_expanded_content<?= $row->link_id ?>"
                                                aria-expanded="false"
                                                aria-controls="link_expanded_content<?= $row->link_id ?>"
                                            >
                                                <strong class="link_title"><?= in_array($row->subtype, ['spotify', 'youtube', 'youtube_live', 'vimeo', 'tiktok', 'twitch', 'soundcloud', 'text', 'mail', 'pdf', 'instagramfeed']) ? $this->language->link->biolink->{$row->subtype}->name : $row->settings->name ?></strong>
                                            </a>

                                            <span class="d-flex align-items-center">
                                                <?php if(!empty($row->location_url)): ?>
                                                <img src="https://www.google.com/s2/favicons?domain=<?= parse_url($row->location_url)['host'] ?>" class="img-fluid mr-1" />
                                                <span class="d-inline-block text-truncate">
                                                    <a href="<?= $row->location_url ?>" class="text-muted" title="<?= $row->location_url ?>"><?= $row->location_url ?></a>
                                                </span>
                                                <?php elseif(!empty($row->url)): ?>
                                                <img src="https://www.google.com/s2/favicons?domain=<?= url($row->url) ?>" class="img-fluid mr-1" />
                                                <span class="d-inline-block text-truncate">

                                                    <a href="<?= url($row->url) ?>" class="text-muted" title="<?= url($row->url) ?>"><?= url($row->url) ?></a>
                                                </span>
                                                <?php endif ?>
                                            </span>

                                        </div>
                                    </div>
                                
                                    <div class="col-3 col-md-2">
                                        <?php if(!in_array($row->subtype, ['mail', 'text', 'youtube', 'vimeo', 'tiktok', 'twitch', 'spotify', 'soundcloud', 'pdf', 'instagramfeed'])): ?>
                                        <a href="<?= url('link/' . $row->link_id . '/statistics') ?>">
                                            <span data-toggle="tooltip" title="<?= $this->language->project->links->clicks ?>" class="badge badge-primary"><i class="fa fa-fw fa-chart-bar mr-1"></i> <?= nr($row->clicks) ?></span>
                                        </a>
                                        <?php endif ?>
                                    </div>

                                    <div class="col-1 d-flex justify-content-end">
                                        <div class="dropdown">
                                            <a href="#" data-toggle="dropdown" class="text-secondary dropdown-toggle dropdown-toggle-simple">
                                                <i class="fa fa-ellipsis-v"></i>

                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="#"
                                                        class="dropdown-item"
                                                        data-toggle="collapse"
                                                        data-target="#link_expanded_content<?= $row->link_id ?>"
                                                        aria-expanded="false"
                                                        aria-controls="link_expanded_content<?= $row->link_id ?>"
                                                    >
                                                        <i class="fa fa-fw fa-pencil-alt"></i> <?= $this->language->global->edit ?>
                                                    </a>

                                                    <?php if($row->subtype !== 'mail'): ?>
                                                        <a href="<?= url('link/' . $row->link_id . '/statistics') ?>" class="dropdown-item"><i class="fa fa-fw fa-chart-bar"></i> <?= $this->language->link->statistics->link ?></a>
                                                    <?php endif ?>

                                                    <a href="#" class="dropdown-item" id="biolink_link_is_enabled_<?= $data->link->link_id ?>" data-row-id="<?= $row->link_id ?>">
                                                        <i class="fa fa-fw fa-bell"></i> <?= $this->language->link->links->switch_status ?>
                                                    </a>

                                                    <?php if($row->subtype == 'link'): ?>
                                                        <a href="#" class="dropdown-item" data-duplicate="true" data-row-id="<?= $row->link_id ?>"><i class="fa fa-fw fa-copy"></i> <?= $this->language->link->links->duplicate ?></a>
                                                    <?php endif ?>

                                                    <a href="#" class="dropdown-item" data-delete="<?= $this->language->global->info_message->confirm_delete ?>" data-row-id="<?= $row->link_id ?>"><i class="fa fa-fw fa-times"></i> <?= $this->language->global->delete ?></a>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <?php if ($row->subtype == 'instagramfeed' && isset($_SESSION['save_status'])): ?>
                                    <div class="collapse mt-3 show" id="link_expanded_content<?= $row->link_id ?>" data-link-subtype="<?= $row->subtype ?>">
                                        <?php require THEME_PATH . 'views/link/settings/' . $row->subtype . '_form.settings.biolink.method.php' ?>
                                    </div>
                                    <?php $_SESSION['save_status'] = false; ?>
                                <?php else: ?>
                                    <div class="collapse mt-3" id="link_expanded_content<?= $row->link_id ?>" data-link-subtype="<?= $row->subtype ?>">
                                        <?php require THEME_PATH . 'views/link/settings/' . $row->subtype . '_form.settings.biolink.method.php' ?>
                                    </div>
                                <?php endif;?>

                            </div>
                        </div>

                    <?php endwhile ?>
                <?php else: ?>

                    <div class="d-flex flex-column align-items-center justify-content-center">
                        <img src="<?= SITE_URL . ASSETS_URL_PATH . 'images/no_data.svg' ?>" class="col-10 col-md-6 col-lg-4 mb-3" alt="<?= $this->language->link->links->no_data ?>" />
                        <h2 class="h4 text-muted"><?= $this->language->link->links->no_data ?></h2>
                    </div>

                <?php endif ?>

            </div>
        </div>
    </div>

    <div class="col-12 col-lg-5 mt-5 mt-lg-0 d-flex justify-content-center">
        <div class="biolink-preview-container">
            <div class="biolink-preview">
                <div class="biolink-preview-iframe-container">
       
                    <iframe id="biolink_preview_iframe" class="biolink-preview-iframe container-disabled-simple" src="<?= url($data->link->url . '?preview&link_id=' . $data->link->link_id) ?>" data-url-prepend="<?= url() ?>" data-url-append="<?= '?preview&link_id=' . $data->link->link_id ?>"></iframe>
                </div>
            </div>
        </div>
    </div>
    
</div>

<?php $html = ob_get_clean() ?>


<?php ob_start() ?>

<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
