<section class="specialists-page">
    <h1><?php echo language_text('Our specialists'); ?></h1>
    <p class="specialists-intro"><?php echo language_text('Meet the team who will take care of you.'); ?></p>
    <?php if (!$arr) { ?>
        <p class="notice"><?php echo language_text('Specialist profiles will be available soon.'); ?></p>
    <?php } else { ?>
        <div class="specialist-grid">
            <?php foreach ($arr as $specialist) { ?>
                <?php preg_match('/^./us', $specialist['name'], $initial); ?>
                <article class="specialist-card">
                    <div class="specialist-avatar" aria-hidden="true"><?php echo htmlspecialchars(isset($initial[0]) ? $initial[0] : '', ENT_QUOTES, 'UTF-8'); ?></div>
                    <div class="specialist-card-content">
                        <p class="specialist-specialty"><?php echo htmlspecialchars($specialist['specialty'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <h2><?php echo htmlspecialchars($specialist['name'], ENT_QUOTES, 'UTF-8'); ?></h2>
                        <?php if ($specialist['bio'] !== '') { ?>
                            <p><?php echo nl2br(htmlspecialchars($specialist['bio'], ENT_QUOTES, 'UTF-8')); ?></p>
                        <?php } ?>
                        <?php $specialistServices = isset($servicesBySpecialist[(int)$specialist['specialist_id']])
                            ? $servicesBySpecialist[(int)$specialist['specialist_id']]
                            : array(); ?>
                        <?php if ($specialistServices) { ?>
                            <h3><?php echo language_text('Services provided'); ?></h3>
                            <ul class="specialist-service-list">
                                <?php foreach ($specialistServices as $service) { ?>
                                    <li>
                                        <a href="service?service_id=<?php echo (int)$service['service_id']; ?>&amp;specialist_id=<?php echo (int)$specialist['specialist_id']; ?>#booking">
                                            <?php echo htmlspecialchars(localized_service_title($service), ENT_QUOTES, 'UTF-8'); ?>
                                        </a>
                                        <span><?php echo htmlspecialchars((string)$service['price'], ENT_QUOTES, 'UTF-8'); ?> €</span>
                                    </li>
                                <?php } ?>
                            </ul>
                        <?php } else { ?>
                            <p class="muted"><?php echo language_text('No services assigned to this specialist.'); ?></p>
                        <?php } ?>
                    </div>
                </article>
            <?php } ?>
        </div>
    <?php } ?>
</section>
