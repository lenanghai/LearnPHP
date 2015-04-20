/**
* Kai Dev
*/
jQuery(document).ready(function ($) {
    "use strict";



    /*=========Custom Taxonomy & Attributes Filter========*/

    AweProductFilter();
    var newObj = {};
    function AweProductFilter() {

        // Filter by Product Category
        var arrcat = new Array();
        var objcat = $('.sidebar.product_cat .nav-cat .check-box label');
        objcat.on('click', function () {
            $(this).attr("stt", "checked");
            var data = $(this).attr("for");
            $('.catalog-content').html('<div class="filter_loading"></div>');
            // Control Data Array
            if (arrcat.indexOf(data) == -1) {
                arrcat.push(data);
            } else {
                arrcat.splice(arrcat.indexOf(data), 1);
            }
            // Convert to string
            var arrC = arrcat.toString();
            $(this).parents(".sidebar.product_cat").attr("data", arrC);
            getFilter();
        });

        // Filter by Manufacture
        var arrman = new Array();
        var objman = $('.sidebar.manufacture .nav-cat .check-box label');
        objman.on('click', function () {
            $(this).attr("stt", "checked");
            var data = $(this).attr("for");
            $('.catalog-content').html('<div class="filter_loading"></div>');
            // Control Data Array
            if (arrman.indexOf(data) == -1) {
                arrman.push(data);
            } else {
                arrman.splice(arrman.indexOf(data), 1);
            }
            // Convert to string
            var arrM = arrman.toString();
            $(this).parents(".sidebar.manufacture").attr("data", arrM);
            getFilter();
        });


        // Filter by Size
        var arrSize = new Array();
        var objSize = $('.sidebar.pa_size .nav-cat .check-box label');
        objSize.on('click', function () {
            $(this).attr("stt", "checked");
            var data = $(this).attr("for");
            $('.catalog-content').html('<div class="filter_loading"></div>');
            // Control Data Array
            if (arrSize.indexOf(data) == -1) {
                arrSize.push(data);
            } else {
                arrSize.splice(arrSize.indexOf(data), 1);
            }
            // Convert to string
            var arrS = arrSize.toString();
            $(this).parents(".sidebar.pa_size").attr("data", arrS);
            getFilter();
        });

        // Filter by Color
        var arrColor = new Array();
        var objColor = $('.sidebar.pa_color .nav-color li a');
        objColor.on('click', function (event) {
            event.preventDefault();

            $(this).attr("stt", "checked");
            var data = $(this).attr("data");
            $('.catalog-content').html('<div class="filter_loading"></div>');
            // Control Data Array
            if (arrColor.indexOf(data) == -1) {
                arrColor.push(data);
            } else {
                arrColor.splice(arrColor.indexOf(data), 1);
            }
            // Convert to string
            var arrCL = arrColor.toString();
            $(this).parents(".sidebar.pa_color").attr("data", arrCL);
            getFilter();
        });




        /*==========  Price Slider ==========*/
        PriceSlider();
        var price = new Array();

        function PriceSlider() {
            if ($('#slider').length) {
                var df_min = $('.default-value').attr("data-min");
                var df_max = $('.default-value').attr("data-max");
                var min = $('#price-f').text();
                var max = $('#price-t').text();
                $("#slider").slider({
                    min: df_min,
                    max: df_max,
                    step: 1,
                    values: [min, max],
                    range: true,
                    slide: function (event, ui) {

                        var $this = $(this),
                                values = ui.values;

                        $('#price-f').text('$' + values[0]);

                        $('#price-t').text('$' + values[1]);

                    }
                });

                var values = $("#slider").slider("option", "values");

                $('#price-f').text('$' + values[0]);

                $('#price-t').text('$' + values[1]);
                $('.sidebar .btn-filter').on('click', function () {
                    $('.catalog-content').html('<div class="filter_loading"></div>');


                    var min = values[0];
                    var max = values[1];
                    price.splice(0, 1, min);
                    price.splice(1, 1, max);
                    var strprice = price.toString();
                    $(this).parents(".sidebar.price").attr("data-price", strprice);
                    var priceData = {
                        'action': 'product_filter',
                        'price': strprice
                    };
                    getFilter();
                });
            }
        }

        // Orderby
        $('#sort').on('change', function () {
            var order = $(this).val();
            $(this).attr("data-sort", order);
            $('.catalog-content').html('<div class="filter_loading"></div>');
            getFilter();
        });

        //Show Page

        $('#show-page').on('change', function (e) {
            var show = $(this).val();
            $('.catalog-content').html('<div class="filter_loading"></div>');
            $(this).attr("data-show", show);
            getFilter();

        });

        // Panigation 

        $('.top-filter .paging-p li a').on('click', function (event) {
            event.preventDefault();
            var page = $(this).text();
            $('.catalog-content').html('<div class="filter_loading"></div>');
            $(this).parents(".paging-p").attr("data-page", page);
            $(".top-filter .paging-p li").removeClass("current");
            $(this).parents(".paging-p li").addClass("current");
            getFilter();
        });




        /*==========Grid View & List View ===========*/
        $('.top-filter .view a').on('click', function (event) {
            event.preventDefault();
            if ($(this).hasClass("view-grid")) {
                $(this).addClass("active");
                $('.view-list').removeClass("active");
                var view = "grid";
                $(this).parents(".view").attr("data-view", view);
                $('.catalog-content').html('<div class="filter_loading"></div>');
                getFilter();
            }
            if ($(this).hasClass("view-list")) {
                $(this).addClass("active");
                $('.view-grid').removeClass("active");
                var view = "list";
                $(this).parents(".view").attr("data-view", view);
                $('.catalog-content').html('<div class="filter_loading"></div>');
                getFilter();
            }

        });

    }

    // Get all data
    var cData = {};
    var catObj = {};
    var manObj = {};
    var colorObj = {};
    var parseData = function getData()
    {
        cData['action'] = 'product_filter';

        // Get Product Cat        
        if ($(".sidebar.product_cat").length)
        {
            var cat = $(".sidebar.product_cat").attr("data");
            cData.product_cat = cat;
        }

        // Get Manufacture       
        if ($(".sidebar.manufacture").length)
        {
            var man = $(".sidebar.manufacture").attr("data");
            cData.manufacture = man;
        }

        // Get Current View
        if ($('.top-filter .view'))
        {
            var view = $('.top-filter .view').attr("data-view");
            cData.view = view;
        }

        // Get number post      
        if ($('.top-filter #show-page').length)
        {
            var show = $('.top-filter #show-page').attr("data-show");
            cData.show = show;
        }


        // Get orderby       
        if ($('.top-filter #sort').length)
        {
            var sort = $('.top-filter #sort').attr("data-sort");
            cData.orderby = sort;
        }


        // Get Current Page
        if ($("ul.paging-p").length) {

            var paged = $("ul.paging-p").attr("data-page");
            cData.paged = paged;


        }

        // Get Price

        if ($(".sidebar.price").length)
        {
            var price = $(".sidebar.price").attr("data-price");
            cData.price = price;
        }

        // Get Size
        if ($('.sidebar.pa_size').length)
        {
            var size = $('.sidebar.pa_size').attr("data");
            cData.size = size;
        }

        // Get Color
        if ($('.sidebar.pa_color').length)
        {
            var color = $('.sidebar.pa_color').attr("data");
            cData.color = color;
        }

        // Return data
        return cData;
    };

    // Filter via ajax action

    var getFilter = function Filter_Product()
    {
        $.ajax(
                {
                    type: 'get',
                    data: parseData(),
                    url: product.url,
                    async: true,
                    success: function (ok) {
                        $('.catalog-content').html(ok);
                    }
                });
        console.log(parseData());
    };




});
