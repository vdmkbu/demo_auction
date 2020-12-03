/*
 *
 *   INSPINIA - Responsive Admin Theme
 *   version 2.7
 *
 */

$(document).ready(function(){
    $('.dataTables-example').DataTable({

        pageLength: 25,
        responsive: true,
        dom: '<"html5buttons"B>lTfgitp',
        language: {
            url: "https://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Russian.json"
        },
        buttons: [
            {extend: 'csv'},
            {extend: 'excel', title: 'ExampleFile'},
            {extend: 'pdf', title: 'ExampleFile'},

            {extend: 'print',
                text: 'Печать',
                customize: function (win){
                    $(win.document.body).addClass('white-bg');
                    $(win.document.body).css('font-size', '10px');

                    $(win.document.body).find('table')
                        .addClass('compact')
                        .css('font-size', 'inherit');
                }
            }
        ]

    });

});

$(document).ready(function () {

    // установим комиссию по умолчанию для всех лотов; не можем этого сделать из php, т.к там считается от суммы f_Sum, что при покупки части не может обработаться
    // пройдем по всем полям "Хочу купить часть"
    if($('.set_part_bid').length) {

        $(".set_part_bid").each(function(i, el) {

            var sum = $(el).val();
            var lot_id = $(el).data('lot');


            // получим ставку по умолчанию из поля .set_bid в модальном окне
            var next_fee = $(".set_bid[data-lot='"+lot_id+"']").val();

            // посчитаем комиссию
            var comission = (sum/100)*next_fee;
            $('#fee_value_'+lot_id+'').html(comission);

        });
    }


    // кнопка "применить" - ставим ставку
    $('.ajax_form').each(function() {

        var owner = this;

        $(this).submit(function(e) {

            e.preventDefault();



            var fields = $(this).serialize();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url:this.action,
                type: this.method,
                data: fields,
                always: function(data) {

                },
                success: function(data) {
                    $(owner).remove();
                    $('#set_bid_'+$(owner).data('id')).hide();
                    $('.modal-body').html(data);
                    window.location.reload();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    var error = jqXHR.responseJSON.error;
                    $(owner).remove();
                    $('#set_bid_'+$(owner).data('id')).hide();
                    $('.modal-body').html(error);
                }
            });



        });



    });

    // переключение ставки в модальном окне
    $('.set_bid').bind("change paste keyup", function(){
        var bid = Number($(this).val());

        var lot_id = $(this).data('lot');

        var max = Number($(this).attr('max'));
        var min = Number($(this).attr('min'));


        if(bid < min) {
            $(this).val(min);
        }

        if(bid > max) {
            $(this).val(max);
        }

        var sum = $(this).data('sum');
        var comission = (sum/100)*bid;

        if(comission) {
            $('#fee_value_'+lot_id+'').html(comission);
        }

    });

    // изменение поля хочу купить часть
    $('.set_part_bid').bind("change paste keyup", function() {

        var bid = Number($(this).val());


        // получим id лота, чтобы связаться с модальной формой
        var lot_id = $(this).data('lot');


        // ВАЛИДАЦИЯ

        var max = Number($(this).attr('max'));
        var min = Number($(this).attr('min'));


        if(bid < min) {
            $(this).val(min);
        }

        if(bid > max) {
            $(this).val(max);
        }

        // копируем значение "хочу купить часть" в модальную форму в атрибут data-sum: используется при переключении +\- значения ставки
        $('#myModal_'+lot_id+'').find('.set_bid').data("sum", bid);


        // получим ставку по умолчанию из поля .set_bid в модальном окне
        var next_fee = $(".set_bid[data-lot='"+lot_id+"']").val();

        // посчитаем комиссию
        var comission = (bid/100)*next_fee;

        // обновим комиссию
        $('#fee_value_'+lot_id+'').html(comission);

    });


    // close lot
    $('.close_lot').click(function(e){
        e.preventDefault();

        var source = $(this).data('source');

        if(confirm("Принять ставку и закрыть лот?")) {

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: source,
                method: "POST",
                success: function(data) {

                    if(data.success) {
                        alert(data.success);
                        window.location.reload();
                    }

                }
            });
        }
    });

    // delete lot
    $('.delete_lot').click(function(e) {
        e.preventDefault();

        var source = $(this).data('source');

        if(confirm("Удалить лот?")) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: source,
                type: "DELETE",
                success: function(data) {
                    if(data) {
                        alert("Лот удален");
                        window.location.reload();
                    }
                }
            });
        }
    });

    // delete enterprice
    $('.delete_enterprice').click(function(e) {
        e.preventDefault();

        var message = $(this).data('message');

        if(confirm("Удалить предприятие?")) {
            $.ajax({
                url: "/netcat/modules/default/actions/d_enter.php",
                method: "POST",
                data: {"message":message},
                success: function(data) {
                    if(data) {
                        alert("Предприятие удалено");
                        window.location.href = '/enterprice/';
                    }
                }
            });
        }

    });

    // calc NDS

    $('.lot_sum').bind("change paste keyup", function(){

        var sum = $(this).val();
        var nds = $('.lot_nds:checked').data('value');
        var max = $(this).attr('max');



        if(Number(sum) > Number(max)) {
            this.value = Number(max);
        }

        if (this.value.match(/[,]/g)) {
            this.value = this.value.replace(/[,]/g, '.');
        }

        if (this.value.match(/[^0-9.]/g)) {
            this.value = this.value.replace(/[^0-9.]/g, '');
        }

        var nds_sum = sum*(nds/100);
        if(!isNaN(nds_sum)) {
            nds_sum = nds_sum.toFixed(2);
            $('#nds_calc').html(nds_sum);
        }
    });


    $('.fee').bind("change paste keyup", function(){
        var val = $(this).val();
        var max = $(this).attr('max');
        var min = $(this).attr('min');


        if(Number(val) > Number(max)) {
            this.value = Number(max);
        }

        if(Number(val) < Number(min)) {
            this.value = Number(min);
        }
    });

    //var hckValue = $('SELECTOR').iCheck('update')[0].checked;
    // https://github.com/fronteed/iCheck#callbacks
    //$('input').on('ifChecked', function(event){alert(event.type + ' callback');});
    $('.i-checks-nds').on('ifChanged', function(event) {


        var checked = event.target.checked;
        var value = event.target.value;

        if(checked) {
            var nds = $(event.target).data('value');
            var sum = $('.lot_sum').val();
            var nds_sum = sum*(nds/100);
            if(!isNaN(nds_sum)) {
                nds_sum = nds_sum.toFixed(2);
                $('#nds_calc').html(nds_sum);
            }
        }
    });

    // включение/отключение лота
    $('.i-checks-checked').on('ifChanged', function(event) {
        var checked = event.target.checked;
        var value = event.target.value;

        if(checked) {
            var message = $(event.target).data('message');

            $.get('/lots/checked_loty_'+message+'.html?isNaked=1',{},function(data){
                $('#lot_check_status').html(data);
            });

        }
    });

    // mask phone
    $('.phone_mask').mask("+79999999999");

    // Add body-small class if window less than 768px
    if ($(this).width() < 769) {
        $('body').addClass('body-small')
    } else {
        $('body').removeClass('body-small')
    }

    // MetisMenu
    $('#side-menu').metisMenu();

    // Collapse ibox function
    $('.collapse-link').on('click', function () {
        var ibox = $(this).closest('div.ibox');
        var button = $(this).find('i');
        var content = ibox.children('.ibox-content');
        content.slideToggle(200);
        button.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
        ibox.toggleClass('').toggleClass('border-bottom');
        setTimeout(function () {
            ibox.resize();
            ibox.find('[id^=map-]').resize();
        }, 50);
    });

    // Close ibox function
    $('.close-link').on('click', function () {
        var content = $(this).closest('div.ibox');
        content.remove();
    });

    // Fullscreen ibox function
    $('.fullscreen-link').on('click', function () {
        var ibox = $(this).closest('div.ibox');
        var button = $(this).find('i');
        $('body').toggleClass('fullscreen-ibox-mode');
        button.toggleClass('fa-expand').toggleClass('fa-compress');
        ibox.toggleClass('fullscreen');
        setTimeout(function () {
            $(window).trigger('resize');
        }, 100);
    });

    // Close menu in canvas mode
    $('.close-canvas-menu').on('click', function () {
        $("body").toggleClass("mini-navbar");
        SmoothlyMenu();
    });

    // Run menu of canvas
    $('body.canvas-menu .sidebar-collapse').slimScroll({
        height: '100%',
        railOpacity: 0.9
    });

    // Open close right sidebar
    $('.right-sidebar-toggle').on('click', function () {
        $('#right-sidebar').toggleClass('sidebar-open');
    });

    // Initialize slimscroll for right sidebar
    $('.sidebar-container').slimScroll({
        height: '100%',
        railOpacity: 0.4,
        wheelStep: 10
    });

    // Open close small chat
    $('.open-small-chat').on('click', function () {
        $(this).children().toggleClass('fa-comments').toggleClass('fa-remove');
        $('.small-chat-box').toggleClass('active');
    });

    // Initialize slimscroll for small chat
    $('.small-chat-box .content').slimScroll({
        height: '234px',
        railOpacity: 0.4
    });

    // Small todo handler
    $('.check-link').on('click', function () {
        var button = $(this).find('i');
        var label = $(this).next('span');
        button.toggleClass('fa-check-square').toggleClass('fa-square-o');
        label.toggleClass('todo-completed');
        return false;
    });



    // Minimalize menu
    $('.navbar-minimalize').on('click', function () {
        $("body").toggleClass("mini-navbar");
        SmoothlyMenu();

    });

    // Tooltips demo
    $('.tooltip-demo').tooltip({
        selector: "[data-toggle=tooltip]",
        container: "body"
    });


    // Full height of sidebar
    function fix_height() {
        var heightWithoutNavbar = $("body > #wrapper").height() - 61;
        $(".sidebar-panel").css("min-height", heightWithoutNavbar + "px");

        var navbarheight = $('nav.navbar-default').height();
        var wrapperHeight = $('#page-wrapper').height();

        if (navbarheight > wrapperHeight) {
            $('#page-wrapper').css("min-height", navbarheight + "px");
        }

        if (navbarheight < wrapperHeight) {
            $('#page-wrapper').css("min-height", $(window).height() + "px");
        }

        if ($('body').hasClass('fixed-nav')) {
            if (navbarheight > wrapperHeight) {
                $('#page-wrapper').css("min-height", navbarheight + "px");
            } else {
                $('#page-wrapper').css("min-height", $(window).height() - 60 + "px");
            }
        }

    }

    fix_height();

    // Fixed Sidebar
    $(window).bind("load", function () {
        if ($("body").hasClass('fixed-sidebar')) {
            $('.sidebar-collapse').slimScroll({
                height: '100%',
                railOpacity: 0.9
            });
        }
    });

    // Move right sidebar top after scroll
    $(window).scroll(function () {
        if ($(window).scrollTop() > 0 && !$('body').hasClass('fixed-nav')) {
            $('#right-sidebar').addClass('sidebar-top');
        } else {
            $('#right-sidebar').removeClass('sidebar-top');
        }
    });

    $(window).bind("load resize scroll", function () {
        if (!$("body").hasClass('body-small')) {
            fix_height();
        }
    });

    $("[data-toggle=popover]")
        .popover();

    // Add slimscroll to element
    $('.full-height-scroll').slimscroll({
        height: '100%'
    })
});


// Minimalize menu when screen is less than 768px
$(window).bind("resize", function () {
    if ($(this).width() < 769) {
        $('body').addClass('body-small')
    } else {
        $('body').removeClass('body-small')
    }
});

// Local Storage functions
// Set proper body class and plugins based on user configuration
$(document).ready(function () {
    if (localStorageSupport()) {

        var collapse = localStorage.getItem("collapse_menu");
        var fixedsidebar = localStorage.getItem("fixedsidebar");
        var fixednavbar = localStorage.getItem("fixednavbar");
        var boxedlayout = localStorage.getItem("boxedlayout");
        var fixedfooter = localStorage.getItem("fixedfooter");

        var body = $('body');

        if (fixedsidebar == 'on') {
            body.addClass('fixed-sidebar');
            $('.sidebar-collapse').slimScroll({
                height: '100%',
                railOpacity: 0.9
            });
        }

        if (collapse == 'on') {
            if (body.hasClass('fixed-sidebar')) {
                if (!body.hasClass('body-small')) {
                    body.addClass('mini-navbar');
                }
            } else {
                if (!body.hasClass('body-small')) {
                    body.addClass('mini-navbar');
                }

            }
        }

        if (fixednavbar == 'on') {
            $(".navbar-static-top").removeClass('navbar-static-top').addClass('navbar-fixed-top');
            body.addClass('fixed-nav');
        }

        if (boxedlayout == 'on') {
            body.addClass('boxed-layout');
        }

        if (fixedfooter == 'on') {
            $(".footer").addClass('fixed');
        }
    }
});

// check if browser support HTML5 local storage
function localStorageSupport() {
    return (('localStorage' in window) && window['localStorage'] !== null)
}

// For demo purpose - animation css script
function animationHover(element, animation) {
    element = $(element);
    element.hover(
        function () {
            element.addClass('animated ' + animation);
        },
        function () {
            //wait for animation to finish before removing classes
            window.setTimeout(function () {
                element.removeClass('animated ' + animation);
            }, 2000);
        });
}

function SmoothlyMenu() {
    if (!$('body').hasClass('mini-navbar') || $('body').hasClass('body-small')) {
        // Hide menu in order to smoothly turn on when maximize menu
        $('#side-menu').hide();
        // For smoothly turn on menu
        setTimeout(
            function () {
                $('#side-menu').fadeIn(400);
            }, 200);
    } else if ($('body').hasClass('fixed-sidebar')) {
        $('#side-menu').hide();
        setTimeout(
            function () {
                $('#side-menu').fadeIn(400);
            }, 100);
    } else {
        // Remove all inline style from jquery fadeIn function to reset menu state
        $('#side-menu').removeAttr('style');
    }
}

// Dragable panels
function WinMove() {
    var element = "[class*=col]";
    var handle = ".ibox-title";
    var connect = "[class*=col]";
    $(element).sortable(
        {
            handle: handle,
            connectWith: connect,
            tolerance: 'pointer',
            forcePlaceholderSize: true,
            opacity: 0.8
        })
        .disableSelection();
}
