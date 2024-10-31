<?php
CFSSMARTSMS::createSection( $prefix, array(
    'title'  => '<div class="nirweb_codestar_icon">'.esc_html__('Scheduled SMS', 'nss').'<span><i class="fas fa-lock"></i>PRO</span></div>',
    'fields' => array(
        array(
            'id'    => 'scheduled_sms',
            'type'  => 'repeater',
            'title' => esc_html__('Scheduled SMS','nss'),
            'fields' => array(
                array(
                    'id' => 'users',
                    'type' => 'select',
                    'options' => 'users',
                    'query_args'  => array(
                        'posts_per_page' => -1
                    ),
                    'multiple' => true,
                    'chosen' => true,
                    'title' => __('Users','nss')
                ),
                array(
                    'id' => 'pattern',
                    'type' => 'text',
                     'title' => __('Pattern','nss')
                ),
                 array(
                     'id' => 'date',
                     'settings' => array(
                         'dateFormat'      => 'mm/dd/yy'
                     ),
                     'dateFormat'      => 'mm/dd/yy',
                     'type' => 'date',
                     'title' => __('Date','nss')
                 )
            )
        ),
    ),
));