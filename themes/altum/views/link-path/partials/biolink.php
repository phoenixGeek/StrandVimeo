<?php defined('ALTUMCODE') || die() ?>

<body class="link-body <?= $data->link->design->background_class ?>" style="<?= $data->link->design->background_style ?>">
    <div class="container animated fadeIn">
        <div class="row d-flex justify-content-center text-center">
            <div class="col-md-8 link-content">

                <header class="d-flex flex-column align-items-center" style="<?= $data->link->design->text_style ?>">

                    <div class="d-flex flex-row align-items-center mt-4">
                        <h1 id="title"><?= $data->link->settings->title ?></h1>
                        <span id="switchTip1" style="display: none;" data-toggle="tooltip" title="<?= \Altum\Language::get()->global->verified ?>" class="link-verified ml-1"><i class="fa fa-fw fa-check-circle fa-1x"></i></span>
                        <?php if($data->user->package_settings->verified && $data->link->settings->display_verified): ?>
                            <span id="switchTip" data-toggle="tooltip" title="<?= \Altum\Language::get()->global->verified ?>" class="link-verified ml-1"><i class="fa fa-fw fa-check-circle fa-1x"></i></span>
                        <?php endif ?>
                    </div>

                    <p id="description"><?= $data->link->settings->description ?></p>
                    
                </header>

                <main id="links" class="mt-4">

                    <?php if($data->links): ?>

                        <?php foreach($data->links as $row): ?>

                            <?php

                            /* Check if its a scheduled link and we should show it or not */
                            if(
                                !empty($row->start_date) &&
                                !empty($row->end_date) &&
                                (
                                    \Altum\Date::get('', null) < \Altum\Date::get($row->start_date, null, \Altum\Date::$default_timezone) ||
                                    \Altum\Date::get('', null) > \Altum\Date::get($row->end_date, null, \Altum\Date::$default_timezone)
                                )
                            ) {
                                continue;
                            }

                            $row->utm = $data->link->settings->utm;

                            ?>

                            <div data-link-id="<?= $row->link_id ?>">
                                <?= \Altum\Link::get_biolink_link($row, $data->user) ?? null ?>
                            </div>

                        <?php endforeach ?>
                    <?php endif ?>

                </main>

            </div>
        </div>
    </div>

    <?= \Altum\Event::get_content('modals') ?>
    
</body>

<?php ob_start() ?>
<script>
    /* Internal tracking for biolink links */
    $('[data-location-url]').on('click', event => {

        let base_url = $('[name="url"]').val();
        let url = $(event.currentTarget).data('location-url');

        $.ajax(`${base_url}${url}?no_redirect`);
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<?php ob_start() ?>

<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<?php if($data->user->package_settings->google_analytics && !empty($data->link->settings->google_analytics)): ?>
    <?php ob_start() ?>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= $data->link->settings->google_analytics ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', '<?= $data->link->settings->google_analytics ?>');
    </script>

    <?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>
<?php endif ?>

<?php if($data->user->package_settings->facebook_pixel && !empty($data->link->settings->facebook_pixel)): ?>
    <?php ob_start() ?>

    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '<?= $data->link->settings->facebook_pixel ?>');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=<?= $data->link->settings->facebook_pixel ?>&ev=PageView&noscript=1"/></noscript>
    <!-- End Facebook Pixel Code -->

    <?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>
<?php endif ?>

