<?php
include_once( 'config.php' );
include_once( 'services.php' );
include_once( 'functions.php' );
?>
<!DOCTYPE html>
<html lang="en" data-theme="emerald">
    <head>
        <meta charset="UTF-8">
        <meta name="google-site-verification" content="<?php echo $google_site_verification; ?>">
        <meta name="referrer" content="same-origin">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title><?php echo $global_status_message; ?> | <?php echo $site_title; ?> Status</title>

        <link rel="apple-touch-icon" href="favicon-180.png">
        <link rel="icon" type="image/png" href="assets/favicon-16.png" sizes="16x16">
        <link rel="icon" type="image/png" href="assets/favicon-32.png" sizes="32x32">
        <link rel="icon" type="image/png" href="assets/favicon-96.png" sizes="96x96">
        <link rel="icon" type="image/png" href="assets/favicon-192.png" sizes="192x192">

        <link href="https://cdn.jsdelivr.net/npm/daisyui@3.9.4/dist/full.css" rel="stylesheet" type="text/css" />
        <script src="https://cdn.tailwindcss.com"></script>

        <style>
        h3 {
            font-size: 1rem;
        }
        </style>
    </head>
    <body class="antialiased text-gray-900">
        <header class="<?php echo $header_bg; ?> text-white p-4 relative z-20 flex flex-col-reverse items-center px-3 py-3 mb-10 md:py-6 md:flex-row md:px-8 md:mb-14">
            <h1 class="text-2xl font-semibold flex justify-center w-full"><?php echo $site_title; ?> Status</h1>
        </header>

        <main class="max-w-6xl px-3 mx-auto mb-8 md:px-6 md:mb-24">

            <div class="flex flex-col items-center w-full mb-8 md:mb-10 lg:mb-14">
                <?php
                $overall_bg_class   = !in_array('DOWN', $all_statuses_today) ? 'bg-emerald-100' : 'bg-red-100';
                $overall_text_class = !in_array('DOWN', $all_statuses_today) ? 'text-emerald-500' : 'text-red-500';
                $border             = !in_array('DOWN', $all_statuses_today) ? '#53FFC6' : '';
                $bg_gradient_from   = !in_array('DOWN', $all_statuses_today) ? '#31D29E' : '';
                $bg_gradient_to     = !in_array('DOWN', $all_statuses_today) ? '#4FE9B5' : '';
                ?>
                <div class="flex items-center justify-center w-10 h-10 rounded-full md:w-12 md:h-12 <?php echo $overall_bg_class; ?>">
                    <div class="w-8 h-8 md:w-10 md:h-10 flex items-center justify-center rounded-full border-t border-[<?php echo $border; ?>] bg-gradient-to-t from-[<?php echo $bg_gradient_from; ?>] to-[<?php echo $bg_gradient_to; ?>]">
                        <svg class="absolute w-3 md:w-4 translate-y-0.5 blur-[2px] <?php echo $overall_text_class; ?>" viewBox="0 0 15 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5.62583 10C5.48793 10.0003 5.35133 9.97201 5.22387 9.91677C5.09642 9.86153 4.98061 9.78042 4.8831 9.6781L0.42671 5.00066C0.326306 4.89904 0.246194 4.77746 0.191048 4.64301C0.135901 4.50855 0.106825 4.36392 0.105515 4.21755C0.104205 4.07117 0.130688 3.92599 0.183419 3.79048C0.23615 3.65496 0.314072 3.53182 0.412639 3.42824C0.511207 3.32466 0.628445 3.24272 0.757514 3.18719C0.886582 3.13167 1.0249 3.10367 1.16439 3.10484C1.30388 3.10601 1.44175 3.13632 1.56995 3.19399C1.69816 3.25167 1.81414 3.33557 1.91112 3.44078L5.62478 7.33773L12.3115 0.323224C12.5084 0.11637 12.7757 0.000103436 13.0543 6.89822e-08C13.333 -0.000103298 13.6003 0.115965 13.7974 0.322672C13.9946 0.529379 14.1054 0.809792 14.1055 1.10222C14.1056 1.39465 13.995 1.67515 13.798 1.882L6.36856 9.6781C6.27105 9.78042 6.15524 9.86153 6.02778 9.91677C5.90033 9.97201 5.76373 10.0003 5.62583 10Z" fill="currentColor"></path>
                        </svg>
                        <svg class="relative w-4" viewBox="0 0 15 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5.62583 10C5.48793 10.0003 5.35133 9.97201 5.22387 9.91677C5.09642 9.86153 4.98061 9.78042 4.8831 9.6781L0.42671 5.00066C0.326306 4.89904 0.246194 4.77746 0.191048 4.64301C0.135901 4.50855 0.106825 4.36392 0.105515 4.21755C0.104205 4.07117 0.130688 3.92599 0.183419 3.79048C0.23615 3.65496 0.314072 3.53182 0.412639 3.42824C0.511207 3.32466 0.628445 3.24272 0.757514 3.18719C0.886582 3.13167 1.0249 3.10367 1.16439 3.10484C1.30388 3.10601 1.44175 3.13632 1.56995 3.19399C1.69816 3.25167 1.81414 3.33557 1.91112 3.44078L5.62478 7.33773L12.3115 0.323224C12.5084 0.11637 12.7757 0.000103436 13.0543 6.89822e-08C13.333 -0.000103298 13.6003 0.115965 13.7974 0.322672C13.9946 0.529379 14.1054 0.809792 14.1055 1.10222C14.1056 1.39465 13.995 1.67515 13.798 1.882L6.36856 9.6781C6.27105 9.78042 6.15524 9.86153 6.02778 9.91677C5.90033 9.97201 5.76373 10.0003 5.62583 10Z" fill="white"></path>
                        </svg>
                    </div>
                </div>

                <h1 class="mt-2 text-xl font-semibold md:text-3xl"><?php echo $global_status_message; ?></h1>

                <div class="flex items-center mt-2">
                    <?php if ( !in_array('DOWN', $all_statuses_today) ): ?>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 mr-1 opacity-50">
                        <path d="M11.983 1.907a.75.75 0 00-1.292-.657l-8.5 9.5A.75.75 0 002.75 12h6.572l-1.305 6.093a.75.75 0 001.292.657l8.5-9.5A.75.75 0 0017.25 8h-6.572l1.305-6.093z"></path>
                    </svg>
                    <?php else: ?>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 mr-1 text-red-500">
                        <path d="M11.983 1.907a.75.75 0 00-1.292-.657l-8.5 9.5A.75.75 0 002.75 12h6.572l-1.305 6.093a.75.75 0 001.292.657l8.5-9.5A.75.75 0 0017.25 8h-6.572l1.305-6.093z"></path>
                    </svg>
                    <?php endif; ?>
                    <time class="text-sm font-medium opacity-75">Last updated on <?php echo date('H:i:s A'); ?></time>
                </div>
            </div>

            <div class="max-w-xl mx-auto space-y-12">
                <div>
                    <div class="relative flex justify-center w-full">
                        <div class="grid w-full grid-cols-1 gap-6 divide-y divide-gray-200 divide-dashed">

                        <?php if ( ! empty($statuses) ): ?>
                            <?php foreach ($statuses as $name => $status): ?>
                                <div class="pt-4 space-y-1 lg:pt-5">
                                    <?php $badge_class = $status ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-800'; ?>
                                    <?php if ( $services[$name] && empty($services[$name]['link']) ): ?>
                                    <a href="<?php echo $services[$name]['url']; ?>" class="flex items-center space-x-1 text-sm font-medium group" target="_blank" rel="noreferrer">
                                        <span class="flex-1 text-base truncate group-hover:underline"><?php echo $name; ?></span>
                                        <div class="inline-block font-medium rounded-full text-xs px-2.5 py-1 <?php echo $badge_class; ?>"><?php echo $status ? 'Online' : 'Down'; ?></div>
                                    </a><?php else: ?>
                                    <p class="flex items-center space-x-1 text-sm font-medium group">
                                        <span class="flex-1 text-base truncate"><?php echo $name; ?></span>
                                        <span class="inline-block font-medium rounded-full text-xs px-2.5 py-1 <?php echo $badge_class; ?>"><?php echo $status ? 'Online' : 'Down'; ?></span>
                                    </p>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="max-w-xl mx-auto mt-12 md:mt-20">
                <h2 class="text-lg font-semibold lg:text-2xl">Incidents by Day (Last <?php echo $days_to_display; ?> Days)</h2>

                <ol class="mt-2 divide-y divide-gray-200 lg:mt-4 divide-dashed">
                    <?php foreach ($all_incidents_by_day as $day => $incidents): ?>
                        <?php $serviceCounts = []; ?>
                        <li class="flex flex-col py-6 text-sm md:flex-row md:py-8 last:border-b">
                            <p class="font-semibold md:w-1/5"><?php echo date('M j', strtotime($day)); ?></p>

                            <?php
                            // Checks if any service went down and counts them.
                            $downCount = 0;
                            foreach ($incidents as $incident):
                                $incident     = array_map('trim', explode('|', $incident));
                                $name         = $incident[1];
                                $name_log     = str_replace(' ', '_', trim(strtolower($name)));
                                $status       = end($incident);

                                // Count how many incidents for each service that went down
                                if ( trim($status) === 'DOWN' ) {
                                    $serviceCounts[$name_log] = ($serviceCounts[$name_log] ?? 0) + 1;
                                }

                                // Count how many incidents any service went down
                                if (trim($status) === "DOWN") {
                                    $downCount++;
                                }
                            endforeach;
                            ?>

                            <div class="w-full mt-4 md:mt-0 md:w-4/5">
                                <?php if ( empty($incidents) || $downCount < 1 ) : ?>
                                    <p class="text-gray-700">No incidents on this day.</p>
                                <?php else: ?>
                                    <?php foreach ($incidents as $incident): ?>
                                        <?php
                                        $incident     = array_map('trim', explode('|', $incident));
                                        $time         = date('H:m T', strtotime($incident[0])); // Get the date part
                                        $name         = $incident[1];
                                        $name_log     = str_replace(' ', '_', trim(strtolower($name)));
                                        $status       = end($incident);
                                        $status_color = ( $status === 'UP' ) ? 'emerald' : 'red';
                                        $status_msg   = ( $status === 'UP' ) ? 'has recovered' : 'appears to be down';

                                        if ( $serviceCounts[$name_log] > 0 ) :
                                        ?>
                                        <div class="w-full mt-4 md:mt-0 md:w-4/5">
                                            <div class="relative w-full mb-2">
                                                <div class="absolute w-0.5 h-full rounded-full bg-gray-150 left-[13px]"></div>
                                                <div class="absolute w-0.5 h-8 -bottom-4 bg-gradient-to-b from-gray-150 to-white rounded-full left-[13px]"></div>

                                                <ol class="relative flex flex-col space-y-8">
                                                    <li>
                                                        <article class="flex space-x-3">
                                                            <div class="rounded-full relative flex items-center justify-center border-2 border-white p-0.5 h-7 w-7 bg-<?php echo $status_color; ?>-100">
                                                                <div class="absolute w-4 h-4 bg-white rounded-full"></div>
                                                                <div class="relative">
                                                                    <svg class="h-5 w-5 text-<?php echo $status_color; ?>-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                                    </svg>
                                                                </div>
                                                            </div>

                                                            <div class="flex flex-col">
                                                                <h3 class="font-semibold"><?php echo $name; ?> <?php echo $status_msg; ?>.</h3>
                                                                <time class="mt-1 text-xs font-medium text-gray-600"><?php echo $time; ?></time>
                                                            </div>
                                                        </article>
                                                    </li>
                                                </ol>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </div>
        </main>

        <footer class="flex flex-col justify-center w-full px-6 mb-4 space-x-2 text-xs text-center text-gray-500">
            <div class="flex py-6 items-center justify-center mx-auto mt-8 space-x-3  md:-mt-6">
                <span>Status page powered by <a href="https://github.com/seb86/self-hosted-status-page" target="_blank">Pure Magic</a></span>
            </div>
        </footer>

    </body>
</html>
