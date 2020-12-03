<?php defined('ALTUMCODE') || die() ?>

<div class="card border-0">
    <div class="card-body">
        <h3 class="h5"><?= $this->language->link->statistics->referrer ?></h3>
        <p class="text-muted mb-3"><?= $this->language->link->statistics->referrer_help ?></p>

        <?php foreach($data->rows as $row): ?>
            <div class="mb-2">
                <div class="d-flex justify-content-between">
                    <div class="text-truncate">
                        <?php if(!$row->referrer): ?>
                            <span><?= $this->language->link->statistics->referrer_direct ?></span>
                        <?php else: ?>
                            <img src="https://www.google.com/s2/favicons?domain=<?= $row->referrer ?>" class="img-fluid mr-1" />
                            <a href="<?= $row->referrer ?>" title="<?= $row->referrer ?>" class="align-middle"><?= $row->referrer ?></a>
                        <?php endif ?>
                    </div>

                    <div>
                        <span class="badge badge-pill badge-primary"><?= nr($row->total) ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>
