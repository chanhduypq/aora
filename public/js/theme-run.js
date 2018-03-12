'use strict';

var $windowWidth = $(window).width(),
    $height = undefined;

function checkWidth() {
	if ($windowWidth > 767) {
		$height = $(window).height() - $('.navbar').outerHeight();
	} else {
		$height = 'auto';
	}
}

jQuery.fn.scrollTo = function (elem) {
	var b = $(elem);
	this.scrollTop(b.position().top + b.height() - this.height());
};

function checkAdresses(element) {
	if ($(element).children('.js-create-new-address').is(':checked')) {
		$(element).parents('.checkout-user').find('.addresses-create').addClass('addresses-create--active');
	} else {
		$(element).parents('.checkout-user').find('.addresses-create').removeClass('addresses-create--active');
	}
}

function checkSameAsShipping(element) {
	checkAdresses(element);
	if (!$(element).children('input').is(':checked')) {
		$(element).parents('.checkout-user').find('.checkout-notsame').addClass('checkout-notsame--active');
	} else {
		$(element).parents('.checkout-user').find('.checkout-notsame').removeClass('checkout-notsame--active');
	}
}

$(document).ready(function () {
	checkWidth();

	$('.item-minus').click(function () {
		var $input = $(this).parent().find('input');
		var count = parseInt($input.val()) - 1;
		count = count < 1 ? 1 : count;
		$input.val(count);
		$input.change();
		return false;
	});

	$('.item-plus').click(function () {
		var $input = $(this).parent().find('input');
		$input.val(parseInt($input.val()) + 1);
		$input.change();
		return false;
	});

	$('.cart-remove').click(function () {
		var $cartRow = $(this).closest('.cart-row');
		$cartRow.hide("slow", function () {
			$(this).remove();
		});
		return false;
	});

	$('.js-search').click(function () {
		$('.search-toggle').toggle('fast');
	});

	$('.js-remove-saved-address').click(function () {
		var $cartRow = $(this).closest('.saved-address');
		$cartRow.hide("slow", function () {
			$(this).remove();
		});
		return false;
	});

	$('.js-height').css('height', $height);

	$('.how').on('click', '.js-toggle-how', function () {
		$('.how').toggleClass('how--active');
		$('.form--promo').toggleClass('how-active');

		return false;
	});

	$('#modal-login').on('click', '.js-toggle-create-acc', function () {
		var $btn = $(this),
		    $form = $('.create-form-mobile'),
		    $modalContent = $(this).parents().find('.modal-content'),
		    $modalHeight = $('.create-form-mobile').outerHeight();

		$btn.toggleClass('btn-create--active');
		$form.toggleClass('active');

		$('#modal-login').animate({
			scrollTop: $('.create-form-mobile').parent().scrollTop() + $('.create-form-mobile').offset().top - $('.create-form-mobile').parent().offset().top
		}, {
			duration: 500,
			complete: function complete(e) {
				console.log("animation completed");
			}
		});

		return false;
	});

	$('.navbar').on('click', '.js-toggle-hamburger', function () {
		$(this).toggleClass('active');
		$('.hamburger').toggleClass('hamburger--active');
		$('body').toggleClass('hamburger--active');

		return false;
	});

	$('#modal-login').on('show.bs.modal', function (e) {

		if ($('.js-toggle-hamburger').hasClass('active')) {
			$('.js-toggle-hamburger').trigger('click');
		}
	});

	$(".js-show-add-new").each(function () {
		checkAdresses($(this));
	});

	checkSameAsShipping($('.js-same-as-shipping'));

	$('.checkout-form').on('click', '.js-show-add-new', function () {
		checkAdresses($(this));
	});

	$('.js-edit-settings').on('click', function () {
		$(this).toggleClass('btn-disabled');
		$(".js-form-control").each(function () {
			if ($(this).attr('disabled')) {
				$(this).removeAttr('disabled');
			} else {
				$(this).attr('disabled', 'disabled');
			}
		});
		$('.js-form-control').toggleClass('form-control-plaintext');
		$('.js-form-control').toggleClass('form-control');
	});

	// $('.js-form-control').on('focusout', function(){
	// 	$('.js-edit-settings').toggleClass('btn-disabled');

	// 	$(".js-form-control").each(function() {
	// 	  	$(this).attr('disabled', 'disabled');
	// 	});

	// 	$('.js-form-control').toggleClass('form-control-plaintext');
	// 	$('.js-form-control').toggleClass('form-control');
	// });

	$('.checkout-form').on('click', '.js-same-as-shipping', function () {
		checkSameAsShipping($(this));
	});

	$('.js-carousel').slick({
		slidesToShow: 5,
		dots: false,
		responsive: [{
			breakpoint: 980,
			settings: {
				dots: false,
				arrows: true,
				centerMode: false,
				centerPadding: 0,
				slidesToShow: 3
			}
		}, {
			breakpoint: 560,
			settings: {
				arrows: true,
				centerMode: false,
				centerPadding: 0,
				slidesToShow: 2
			}
		}]
	});

	$('.attribute').change(function() {
		$('#click_group').val($(this).data('group'));
        change_attribute(false);
	});
});
function change_attribute(first) {
    $('.item-order').removeClass('hide');
    $('.item-price').removeClass('hide');
    $('.item-not-available').hide();
    $('#status-old').hide();

    var selector = getAttributeSelector();
    var new_asin = $('img#'+selector);
    var sgd_price = parseFloat(new_asin.data('price')) * 1.4302; //ToDo: change this from settings
    if (!sgd_price) {
        var groups = $('.dl_attr_groups');
        for(var i=0; i< groups.length;i++) {
            if(i == $('#click_group').val()) {
                continue;
            }
            var cur_attrs = $(groups[i]).find('input.attribute');
            for(var ii=0;ii<cur_attrs.length;ii++) {
                if($(cur_attrs[ii]).val() == '') {
                    continue;
                }
                var attributes = [];
                for(var j=0; j< groups.length;j++) {
                    if(i==j) {
                        attributes.push($(cur_attrs[ii]).val());
                    } else {
                        attributes.push($(groups[j]).find('input.attribute:checked').val());
                    }
                }
                attributes = attributes.sort();
                var selector = attributes.join('-');
                var new_asin = $('img#'+selector);
                var sgd_price = new_asin.data('price');
                if (sgd_price) {
                    $(cur_attrs[ii]).prop('checked', true);
                    $(cur_attrs[ii]).closest('.dl_attr_groups').find('.active').removeClass('active');
                    $(cur_attrs[ii]).parent().addClass('active');
                    $('#click_group').val($(cur_attrs[ii]).data('group'));
                    change_attribute(false);
                    return true;
                }
            }
        }
        // $('.item-order').addClass('hide');
        // $('.item-price').addClass('hide');
        // $('.item-not-available').show();
    } else {
        $('#item-price-converted').html(sgd_price.toFixed(2));
        $('#item-price-original').html(new_asin.data('price'));
    }

    $('#product-variant').val(selector);
    $('#product-id').val(new_asin.data('asin'));
    $('#product-image').val(new_asin.attr('src'));
    $('#product-price').val(new_asin.data('price'));
    $('#product-title').val(new_asin.data('title'));
    $('.item-title').html(new_asin.data('title'));
    if(new_asin.data('status') == 'used') {
        $('#status-old').show();
	}
    $('.item-thumb').fadeOut(function() {
        $('.item-thumb img').attr('src', new_asin.attr('src'));
        $('.item-thumb').fadeIn();
    });

    if(first) {
        $('.attribute:checked').parent().addClass('active');
    }
    loadAvaibleAttributes();
}

function loadAvaibleAttributes() {
	var groups = $('.dl_attr_groups');
	for(var i=0; i< groups.length;i++) {
		var cur_attrs = $(groups[i]).find('input.attribute');
		for(var ii=0;ii<cur_attrs.length;ii++) {
		    if($(cur_attrs[ii]).val() == '') {
		        continue;
            }
            var attributes = [];
            for(var j=0; j< groups.length;j++) {
				if(i==j) {
                    attributes.push($(cur_attrs[ii]).val());
				} else {
                    attributes.push($(groups[j]).find('input.attribute:checked').val());
				}
            }
            attributes = attributes.sort();
            var selector = attributes.join('-');
            var new_asin = $('img#'+selector);
            var sgd_price = new_asin.data('price');
            if (!sgd_price) {
                $(cur_attrs[ii]).parent().addClass('disable');
            }else{
                $(cur_attrs[ii]).parent().removeClass('disable');
            }
		}
	}
}

function getAttributeSelector()
{
	var attributes = [];
	$('input.attribute:checked').each(function() {
		if($(this).val() != '') {
            attributes.push($(this).val());
        }
	});
	attributes = attributes.sort();

	var selector = attributes.join('-');

	return selector;
}

$(window).on('resize', function () {
	checkWidth();
	$('.js-height').css('height', $height);
});
//# sourceMappingURL=theme-run.js.map
