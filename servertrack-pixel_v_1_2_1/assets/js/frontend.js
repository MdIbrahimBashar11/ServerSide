jQuery(document).ready(function($) {

    // Track if AddToCart was already fired to prevent duplicates
    var addToCartFired = false;
    var addToCartTimeout = null;
    var lastAddToCartProduct = null;
    var lastAddToCartTime = 0;

    // Get custom triggers from settings
    var addToCartTriggers = (typeof eventrixData !== 'undefined' && eventrixData.addToCartTriggers) ? eventrixData.addToCartTriggers : [];
    var customEvents = (typeof eventrixData !== 'undefined' && eventrixData.customEvents) ? eventrixData.customEvents : [];

    // Build dynamic selectors from triggers
    function buildTriggerSelector(triggers) {
        var selectors = [];
        var classSelectors = [];
        var nameSelectors = [];
        var idSelectors = [];
        var hrefSelectors = [];
        var dataSelectors = [];

        if (!triggers || triggers.length === 0) {
            // Default fallback selectors
            return {
                class: 'button[class*="add-to-cart"], a[class*="add-to-cart"], button.single_add_to_cart_button',
                name: 'button[name="add-to-cart"], input[name="add-to-cart"]',
                id: '#addtocart',
                href: 'a[href*="add-to-cart"]',
                data: '[data-product_id][class*="cart"], [data-product_id][name*="cart"]'
            };
        }

        triggers.forEach(function(trigger) {
            var value = trigger.value || '';
            var type = trigger.type || 'class';

            if (!value) return;

            switch(type) {
                case 'class':
                    classSelectors.push('.' + value);
                    classSelectors.push('[class*="' + value + '"]');
                    break;
                case 'name':
                    nameSelectors.push('[name="' + value + '"]');
                    nameSelectors.push('[name*="' + value + '"]');
                    break;
                case 'id':
                    idSelectors.push('#' + value);
                    break;
                case 'href':
                    hrefSelectors.push('[href*="' + value + '"]');
                    break;
                case 'data-attribute':
                    dataSelectors.push('[data-' + value + ']');
                    break;
            }
        });

        return {
            class: classSelectors.join(', '),
            name: nameSelectors.join(', '),
            id: idSelectors.join(', '),
            href: hrefSelectors.join(', '),
            data: dataSelectors.join(', ')
        };
    }

    // Build selector strings for jQuery
    var triggerSelectors = buildTriggerSelector(addToCartTriggers);
    var buttonSelectors = [];
    var anchorSelectors = [];

    // Build class-based selectors
    if (triggerSelectors.class) {
        var classParts = triggerSelectors.class.split(',');
        classParts.forEach(function(cls) {
            cls = cls.trim();
            if (cls) {
                buttonSelectors.push('button' + cls);
                anchorSelectors.push('a' + cls);
            }
        });
    }

    // Build name-based selectors
    if (triggerSelectors.name) {
        var nameParts = triggerSelectors.name.split(',');
        nameParts.forEach(function(nm) {
            nm = nm.trim();
            if (nm) {
                buttonSelectors.push('button' + nm);
                anchorSelectors.push('a' + nm);
            }
        });
    }

    // Build id-based selectors
    if (triggerSelectors.id) {
        var idParts = triggerSelectors.id.split(',');
        idParts.forEach(function(id) {
            id = id.trim();
            if (id) {
                buttonSelectors.push('button' + id);
                anchorSelectors.push('a' + id);
            }
        });
    }

    // Build href-based selectors (anchor tags only)
    if (triggerSelectors.href) {
        var hrefParts = triggerSelectors.href.split(',');
        hrefParts.forEach(function(href) {
            href = href.trim();
            if (href) {
                anchorSelectors.push('a' + href);
            }
        });
    }

    // Build data-attribute selectors
    if (triggerSelectors.data) {
        var dataParts = triggerSelectors.data.split(',');
        dataParts.forEach(function(data) {
            data = data.trim();
            if (data) {
                buttonSelectors.push('button' + data);
                anchorSelectors.push('a' + data);
            }
        });
    }

    // Fallback to form submit
    var formSelector = 'form.woocommerce-product-form, form[class*="cart"], form[class*="add-to-cart"], form[class*="variations_form"]';
    
    // Universal Add to Cart listener - Form submit (catches all methods)
    $(document).on('submit', formSelector, function(e) {
        var $form = $(this);
        
        // Prevent double-firing
        if (addToCartFired) return;
        addToCartFired = true;
        clearTimeout(addToCartTimeout);
        addToCartTimeout = setTimeout(function() { addToCartFired = false; }, 2000);

        var product_id =
            $form.find('input[name="add-to-cart"]').val() ||
            $form.find('input[name="product_id"]').val() ||
            $form.find('button[name="add-to-cart"]').val() ||
            $form.data('product_id') ||
            null;

        var quantity =
            parseInt($form.find('input.qty').val()) ||
            parseInt($form.find('input[name="quantity"]').val()) ||
            1;

        if (product_id) {
            triggerAddToCart(product_id, quantity, $form);
        }
    });

    // Button click detection (buttons)
    if (buttonSelectors.length > 0) {
        $(document).on('click', buttonSelectors.join(', '), function(e) {
            if (addToCartFired) return;
            
            var $el = $(this);
            var $form = $el.closest('form');

            // Skip if this is a form submit button (form submit will handle it)
            if ($el.attr('type') === 'submit' && $form.length) {
                return;
            }

            // Mark as fired
            addToCartFired = true;
            clearTimeout(addToCartTimeout);
            addToCartTimeout = setTimeout(function() { addToCartFired = false; }, 2000);

            var product_id =
                $el.val() ||
                $el.data('product_id') ||
                $el.data('id') ||
                $el.closest('[data-product_id]').data('product_id') ||
                $form.find('input[name="add-to-cart"]').val() ||
                $form.find('input[name="product_id"]').val() ||
                null;

            // Extract product_id from href if it's a link-style button
            if (!product_id) {
                var href = $el.attr('href') || '';
                var match = href.match(/[?&]add-to-cart=(\d+)/);
                if (match) {
                    product_id = match[1];
                }
            }

            var quantity =
                parseInt($el.data('quantity')) ||
                parseInt($form.find('input.qty').val()) ||
                parseInt($form.find('input[name="quantity"]').val()) ||
                1;

            if (product_id) {
                triggerAddToCart(product_id, quantity, $el);
            }
        });
    }

    // Anchor tag click detection (links with add-to-cart)
    if (anchorSelectors.length > 0) {
        $(document).on('click', anchorSelectors.join(', '), function(e) {
            if (addToCartFired) return;

            var $el = $(this);
            
            // Mark as fired
            addToCartFired = true;
            clearTimeout(addToCartTimeout);
            addToCartTimeout = setTimeout(function() { addToCartFired = false; }, 2000);

            // Extract product_id from data attribute
            var product_id =
                $el.data('product_id') ||
                $el.data('id') ||
                $el.closest('[data-product_id]').data('product_id') ||
                null;

            // Extract product_id from href if available
            if (!product_id) {
                var href = $el.attr('href') || '';
                var match = href.match(/[?&]add-to-cart=(\d+)/);
                if (match) {
                    product_id = match[1];
                }
            }

            var quantity =
                parseInt($el.data('quantity')) ||
                parseInt($el.attr('data-quantity')) ||
                1;

            if (product_id) {
                triggerAddToCart(product_id, quantity, $el);
            }
        });
    }

    // Centralized AddToCart trigger function with duplicate prevention
    function triggerAddToCart(product_id, quantity, $context) {
        if (!product_id) return;

        // Prevent duplicate firing (same product within 1 second)
        var now = Date.now();
        var timeDiff = now - lastAddToCartTime;
        if (lastAddToCartProduct === String(product_id) && timeDiff < 1000) {
            console.log('EVENTRIX AddToCart: Duplicate prevented (same product within 1s)');
            return;
        }
        lastAddToCartProduct = String(product_id);
        lastAddToCartTime = now;

        // Get price from various possible locations (prioritize data attributes first)
        var price = 0;
        
        // FIRST: Try to get price from data attributes or context (most reliable)
        if ($context && $context.length) {
            var dataPrice = $context.data('price') || 
                           $context.attr('data-price') ||
                           $context.find('[data-price]').first().data('price') ||
                           $context.find('[data-price]').first().attr('data-price');
            if (dataPrice) {
                price = parseFloat(dataPrice) || 0;
            }
        }
        
        // SECOND: Get price from DOM (prioritize sale price over regular price)
        if (!price) {
            
            // 1. Try sale price with --sale class (most specific)
            var $salePriceEl = $('.summary .woocommerce-Price-amount--sale bdi, .woocommerce-Price-amount--sale .amount').first();
            if ($salePriceEl.length) {
                var salePriceText = $salePriceEl.text();
                var salePriceMatch = salePriceText.match(/(\d+(?:[.,]\d{1,2})?)/);
                if (salePriceMatch) {
                    price = parseFloat(salePriceMatch[1].replace(',', '.')) || 0;
                }
            }
            
            // 2. Try price inside <ins> tag (sale price in WooCommerce)
            if (!price) {
                var $insPriceEl = $('.summary ins .woocommerce-Price-amount bdi, .price ins .woocommerce-Price-amount bdi, .price ins .amount, ins .woocommerce-Price-amount bdi').first();
                if ($insPriceEl.length) {
                    var insPriceText = $insPriceEl.text();
                    var insPriceMatch = insPriceText.match(/(\d+(?:[.,]\d{1,2})?)/);
                    if (insPriceMatch) {
                        price = parseFloat(insPriceMatch[1].replace(',', '.')) || 0;
                    }
                }
            }
            
            // 3. Try to get prices excluding those in del tags (filter manually)
            if (!price) {
                var $allPrices = $('.summary .woocommerce-Price-amount bdi, .price .woocommerce-Price-amount bdi');
                // Filter out prices inside del tags
                var $validPrices = $allPrices.filter(function() {
                    return $(this).closest('del').length === 0;
                });
                
                if ($validPrices.length > 1) {
                    // If multiple prices, take the last one (usually sale price on product pages)
                    var $lastPriceEl = $validPrices.last();
                    var lastPriceText = $lastPriceEl.text();
                    var lastPriceMatch = lastPriceText.match(/(\d+(?:[.,]\d{1,2})?)/);
                    if (lastPriceMatch) {
                        price = parseFloat(lastPriceMatch[1].replace(',', '.')) || 0;
                    }
                } else if ($validPrices.length === 1) {
                    // Only one valid price, use it
                    var singlePriceText = $validPrices.text();
                    var singlePriceMatch = singlePriceText.match(/(\d+(?:[.,]\d{1,2})?)/);
                    if (singlePriceMatch) {
                        price = parseFloat(singlePriceMatch[1].replace(',', '.')) || 0;
                    }
                }
            }
            
            // 4. Final fallback - any price element not in del tag
            if (!price) {
                var $priceEl = $('.summary .woocommerce-Price-amount bdi:not(del bdi), .price .amount:not(del .amount), .product-price .amount').first();
                if ($priceEl.length) {
                    var priceText = $priceEl.text();
                    var priceMatch = priceText.match(/(\d+(?:[.,]\d{1,2})?)/);
                    if (priceMatch) {
                        price = parseFloat(priceMatch[1].replace(',', '.')) || 0;
                    }
                }
            }
        }

        // Get product name
        var item_name = '';
        var $nameEl = $('.product_title, h1.product_title, h1.entry-title, .product-title').first();
        if ($nameEl.length) {
            item_name = $.trim($nameEl.text());
        }

        console.log('EVENTRIX AddToCart detected:', product_id, 'Qty:', quantity, 'Price:', price);

        var currency = (typeof eventrixData !== 'undefined' && eventrixData.currency) ? eventrixData.currency : 'USD';

        if (window.st) {
            var data = {
                value: price * quantity,
                currency: currency,
                content_ids: [String(product_id)],
                content_type: 'product'
            };
            window.st('track', 'AddToCart', data, {});
        }
    }

    // Custom Events: Track on URL patterns
    if (customEvents && customEvents.length > 0) {
        var currentPath = window.location.pathname;
        
        customEvents.forEach(function(event) {
            var eventUrl = event.custom_url || '';
            var eventName = event.custom_event_name || '';
            var eventValue = parseFloat(event.value) || 0;

            if (!eventUrl || !eventName) return;

            var urlPattern = eventUrl;
            urlPattern = urlPattern.replace(/^\/+|\/+$/g, '');
            currentPath = currentPath.replace(/^\/+|\/+$/g, '');

            if (currentPath.indexOf(urlPattern) !== -1 || currentPath === urlPattern || urlPattern === '') {
                if (window.st) {
                    var customData = {
                        value: eventValue,
                        currency: (typeof eventrixData !== 'undefined' && eventrixData.currency) ? eventrixData.currency : 'USD'
                    };
                    window.st('track', eventName, customData, {});
                    console.log('EVENTRIX Custom Event fired:', eventName, 'on URL:', currentPath);
                }
            }
        });
    }

});
