(function($) {

    var locolized_data = icon_selector_locolized_data,
        ajax_url = locolized_data.ajax_url,
        $icon_selector = $('.mk-ip'),
        $search_input = $('.mk-ip-search-input'),
        $close_btn = $('.mk-ip-header-close-btn'),
        $cancel_btn = $('.mk-ip-cancel-btn'),
        $save_btn = $('.mk-ip-save-btn'),
        $small_view = $('.mk-ip-lib-view-small'),
        $large_view = $('.mk-ip-lib-view-large'),
        $lib_container = $('.mk-ip-lib-wrap'),
        pag_start = 0,
        pag_count = 200,
        global_index = pag_start,
        is_inf_scroll_active = true,
        $last_selected_icon = '',
        $vc_value_input = '',
        $vc_value_view = '',
        $current_svg = '',
        all_icons = '',
        $lib = $('.mk-ip-lib');

    // Open Icon Selector
    $('body').on('click', '.mk-vc-icon-selector-btn', function(e) {
        e.preventDefault();
        $vc_value_input = $(this).siblings('.icon_selector_field');
        $vc_value_view = $(this).siblings('.mk-vc-icon-selector-view-wrap').find('.mk-vc-icon-selector-view');
        $current_svg = $vc_value_view.children('svg').clone();
        // setTimeout( function() {
            $icon_selector.fadeIn(300);
        // }, 300);
        $search_input.focus();
        init_icon_selector();
    });

    // Save Icon Selector
    $save_btn.on('click', function() {
        var icon_class_name = $last_selected_icon.find('svg').attr('data-name'),
            $icon_srouce = $last_selected_icon.find('svg').clone();
        $vc_value_input.val(icon_class_name);
        $vc_value_view.empty().append($icon_srouce);
        $icon_selector.fadeOut(300);
    });

    // Close Icon Selector
    $close_btn.add( $cancel_btn ).on('click', function() {
        $icon_selector.fadeOut(300);
    });

    // Search Icon Selector
    $search_input.on('keyup', _.debounce(function (e) {
        if ( $.trim( $search_input.val() ) === '' ) {
            is_inf_scroll_active = true;
            display_list_of_icons(true);
        } else {
            display_search_of_icons( $search_input.val() );
        }
    }, 500));

    // View buttons
    $small_view.on('click', function() {
        $(this).addClass('mk-selected').siblings().removeClass('mk-selected');
        $lib.removeClass('mk-ip-lib-large').addClass('mk-ip-lib-small')
    });
    $large_view.on('click', function() {
        $(this).addClass('mk-selected').siblings().removeClass('mk-selected');
        $lib.removeClass('mk-ip-lib-small').addClass('mk-ip-lib-large')
    });

    // Infinite Scrolling
    $lib_container.scroll( function() {
       if ( $lib_container.scrollTop() + $lib_container.height() > $lib.prop('scrollHeight') - 100 && is_inf_scroll_active) {
            display_list_of_icons(false);
       }
    });

    // Select Icon
    $lib.on('click', '.mk-ip-lib-item', function() {
        handle_selected_icon( this );
    });


    function init_icon_selector() {
        localforage.getItem('mk_jupiter_icons').then( function(value) {
            if ( value ) {
                display_list_of_icons(true);
            } else {
                cache_all_icons();
            }
        }).catch(function(err) {
            console.log(err);
        });
    }

    function display_list_of_icons(clear) {

        var clear = clear || false;

        if ( clear ) {
            $lib.empty();
            global_index = pag_start;
            is_inf_scroll_active = true;
            $search_input.val('');
            $lib.append( '<li class="mk-ip-lib-item mk-ip-lib-item-first"><div class="mk-ip-lib-item-inner"><div class="mk-ip-lib-item-icon">' + $current_svg[0].outerHTML + '</div></div></li>' );
            handle_selected_icon( $lib.find('.mk-ip-lib-item-first')[0] );
        }

        var initated_start_index = global_index;

        localforage.getItem('mk_jupiter_icons').then( function(data) {
            var icons = '';
            var loop_index = 0;
            $.each( data, function(name, source) {
                if ( loop_index > initated_start_index + pag_count ) {
                    return false;  // Break when loaded the amount of pag_count
                }
                if ( loop_index < initated_start_index  ) {
                    loop_index++;  // Loop until index is at desired position
                } else {
                    icons += '<li class="mk-ip-lib-item"><div class="mk-ip-lib-item-inner"><div class="mk-ip-lib-item-icon">' + source + '</div></div></li>';
                    global_index++;  // Keep track of loaded icons
                    loop_index++;
                }
            });
            if ( is_inf_scroll_active ) {
                $lib.append(icons);
                
            }
        });
    }

    function display_search_of_icons(icon_name) {
        is_inf_scroll_active = false;
        $lib.empty()
        localforage.getItem('mk_jupiter_icons').then( function(data) {
            var icons = '';
            var regex = new RegExp('-' + icon_name + '-', 'i');
            $.each( data, function(name, source) {
                if ( regex.test(name) ) {
                    icons += '<li class="mk-ip-lib-item"><div class="mk-ip-lib-item-inner"><div class="mk-ip-lib-item-icon">' + source + '</div></div></li>';
                }
            });
            $lib.append(icons);
        });
    }

    function cache_all_icons() {
        jQuery.ajax({
            method: "POST",
            url: ajax_url,
            data: {
                pagination_start: 0,
                pagination_count: -1,
                icon_family: 'all',
                action: 'mk_get_icons_list',
            }
        }).success( function( obj ) {
            localforage.setItem('mk_jupiter_icons', obj.data).then( function (value) {
                console.log('Icons were cached.');
                display_list_of_icons();
            }).catch(function(err) {
                console.log('Icons were NOT cached: ' + err);
            });
        });
    }

    function handle_selected_icon(elem) {
        var $this = $(elem);
        if ( $last_selected_icon instanceof jQuery ) {
            $last_selected_icon.removeClass('mk-selected');
        }
        $last_selected_icon = $this;
        $this.addClass('mk-selected');
    }
    


}(jQuery));