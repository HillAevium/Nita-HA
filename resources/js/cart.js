// This gets set to a value within the document.
var profiles;
var info;
var params;

// Global ref to the controller
var controller;

// TODO
// Drop the add profile button when not on the cart page
// Provide support for editing a single program without
// causing the rest of the programs to go to edit mode
// Add history support

// FIXME
// Removing an item from the cart on the review page does
// not remove it from the billing list, need to refresh
// the cart.

if(window.location.pathname == '/MyCart') {
    $(document).ready(
        function() {
            // UI Controller
            controller = new Controller({
                back:       '#back',
                forward:    '#continue',
                login:      '#login',
                register:   '#register',
                display:    '#display',
                review:     '#review',
                billing:    '#billing',
                finish:     '#finish',
                print:      '#print',
                remove:     '.item_remove',
                edit:       '.item_edit',
                addProfile: '#add_profile',
                container:  '#cart_container',
                billContainer: '#billing_container'
            });
            controller.go();
        }
    );
}

// UI Controller
function Controller(bindings) {
    this.programs        = new Array();
    this.selectionModels = new Array();
    this.attendeeLists   = new Array();
    this.bindings        = bindings;
    this.place = "";
    
    this.go = function() {
        this.cart = new ShoppingCart();
        this.cart.bind(bindings);
        this.place = "#display";
    };
    
    this.addProfile = function() {
        $("#cart_pane").hide();
        $("#forms_container").show();
        $('#error_container').html('');
        $('#response_message').html('');
        $("#submit_form").unbind('click');
        $("#submit_form").click(function(event) {
            $("#profile_form").ajaxSubmit();
        });
        $("#profile_form").clearForm();
        $("#profile_form").show();
        $(document).ajaxComplete(function(e, xhr, setting) {
            switch(xhr.status) {
                case 201 : // CREATED
                    alert(xhr.responseText);
                    var data = JSON.parse(xhr.responseText);
                    controller.onAddProfile(data.id, data.name);
                    $("#forms_container").hide();
                    $("#cart_pane").show();
                    break;
                case HTTP_BAD_REQUEST :
                    $('#error_container').html(xhr.responseText);
                    break;
            }
        });
    };
    
    this.addProgram = function(programId, element, spaces) {
        var model = new SelectionModel(profiles, spaces);
        var list = new AttendeeList(model, element);
        list.bind();
        list.render();
        
        this.programs.push(programId);
        this.selectionModels.push(model);
        this.attendeeLists.push(list);
        
        return list;
    };
    
    this.onPlaceChange = function(place) {
        switch(place) {
            case '#display' :
                break;
            case '#review' : 
                break;
            case '#billing' :
                break;
            case '#finish' :
                break;
        }
    };
    
    this.onAddProfile = function(id, name) {
        for(var i in this.selectionModels) {
            this.selectionModels[i].add(id, name);
            this.attendeeLists[i].render();
        }
    };
    
    this.onForward = function() {
        switch(this.cart.view) {
            case 'display' :
                this.onSubmitSelections();
                break;
            case 'review' :
                this.onReviewComplete();
                break;
            case 'billing' :
                this.onBillingSubmit();
                break;
        }
    };
    
    this.onBack = function() {
        switch(this.cart.view) {
            case 'review' :
                this.onDisplayCart();
                break;
            case 'billing' :
                if(params.display == 'single') {
                    this.onDisplayCart();
                } else {
                    this.cart.renderReview();
                }
                break;
        }
    };
    
    this.onBillingSubmit = function() {
        // Pull the card info
        var ccInfo = {
                name:   $('#credit_card_box input[name="name"]').val(),
                number: $('#credit_card_box input[name="number"]').val(),
                csv:    $('#credit_card_box input[name="csv"]').val(),
                month:  $('#credit_card_box select[name="month"] :selected').val(),
                year:   $('#credit_card_box select[name="year"] :selected').val()
        };
        
        // Submit final order
        $.post('/cart/finish', ccInfo, function(data, status, xhr) {
            switch(xhr.status) {
                case 201 : // CREATED
                    controller.onFinish(data);
                    break;
                case 400 : // BAD_REQUEST
                    alert("Errors");
                    break;
                default : // Unhandled Response
                    alert("Unhandled Response");
                    break;
            }
        });
    };
    
    this.onDisplayCart = function() {
        this.cart.renderDisplay();
    };
    
    this.onFinish = function(message) {
        this.cart.renderFinish(message);
    };
    
    this.onPrint = function() {
        alert("Print Me!");
    };
    
    this.onReviewComplete = function() {
        this.cart.renderBilling();
    };
    
    this.onSubmitSelections = function() {
        var items = new Array();
        // Only a multi cart display has information that
        // the server doesn't already know about.
        if(params.display == 'multi') {
            var i, j;
            for(i in this.programs) {
                var programId = this.programs[i];
                var profiles = this.selectionModels[i].selectedList;
                if(profiles.length > 0) {
                    items.push({ programId: programId , profiles: profiles});
                }
            }
        }
        
        $.post('/cart/process', {json: JSON.stringify(items)}, function(data, status, xhr) {
            switch(xhr.status) {
                case 202 : // ACCEPTED
                    var json = JSON.parse(data);
                    controller.billing = json.billing;
                    controller.details = json.details;
                    if(params.display == 'single') {
                        controller.cart.renderBilling();
                    } else {
                        controller.cart.renderReview();
                    }
                    break;
                case 400 : // BAD_REQUEST
                    alert("Errors");
                    break;
                default : // Unhandled Response
                    alert("Unhandled Response");
                    break;
            }
        });
    };
    
    this.onRemoveProgram = function(rowid) {
        var row = $('#' + rowid).parents('div.cart_row');
        if(params.display == 'multi') {
            var i = jQuery.inArray(row.attr('id'),controller.programs);
            if(i == -1) { alert('cart error'); return; }
            
            this.selectionModels.splice(i, 1);
            this.attendeeLists.splice(i, 1);
            this.programs.splice(i, 1);
        }
        row.slideUp(400, function() {
            var count = $("div.cart_row").length;
            if(count == 1) {
                row.empty();
                row.html("You have no items in your cart.");
                row.slideDown();
            } else {
                row.remove();
            }
        });
        $.post('/cart/remove', {rowid: rowid});
    };
    
    // Handlers for AttendeeList events

    this.onAddAttendee = function(list, selectionModel, id) {
        var selectCount = selectionModel.selectedList.length;
        var maxCount = selectionModel.max;
        
        selectionModel.select(id);
        if(selectCount < (maxCount - 1)) {
            list.add();
        } else {
            list.render();
        }
    };
    
    this.onChangeAttendee = function(list, selectionModel, id, oldId) {
        selectionModel.unselect(oldId);
        selectionModel.select(id);
        list.render();
    };

    this.onRemoveAttendee = function(list, selectionModel, id) {
        var selectCount = selectionModel.selectedList.length;
        var maxCount = selectionModel.max;
        
        selectionModel.unselect(id);
        if(selectCount < maxCount) {
            list.remove();
        } else {
            list.render();
        }
    };
    
    this.onSelectAttendee = function(list, selectionModel, event) {
        var val = $(event.target).val();
        var lastVal = $(event.target).children(':nth-child(2)').val();
        var selectCount = selectionModel.selectedList.length;
        var maxCount = selectionModel.max;
        
        if(val == 'Remove') {
            this.onRemoveAttendee(list, selectionModel, lastVal);
        } else {
            var text = $(event.target).children(':first').text();
            if(text == 'Remove') {
                this.onChangeAttendee(list, selectionModel, val, lastVal);
            } else {
                this.onAddAttendee(list, selectionModel, val);
            }
        }
    };
}

// ShoppingCart Composite

function ShoppingCart() {
    this.bindings = null;
    this.container = null;
    this.view = 'display';
    this.section = 'cart';
    
    this.bind = function(bindings) {
        this.bindings = bindings;
        
        // Bind the Remove/Edit Item button
        $(bindings.remove).click(function(event) {
            controller.onRemoveProgram(event.target.id);
            controller.onBack();
            return false;
        });
        $(bindings.edit).click(function(event) {
            controller.onBack();
            return false;
        });
        
        // Setup the cart cart UI
        // single display carts do not setup selection
        // models or attendee widgets
        this.container = $(bindings.container);
        var i = 0;
        this.container.children().each(function() {
            if(params.display == 'multi') {
                var element = $(this).find('#attendees');
                controller.addProgram(this.id, element, params.spaces[i++]);
            }
        });
        
        // Next we bind buttons. Login/Register buttons
        // bind to a page reload bringing the user to the
        // respective pages. An authenticated user will have
        // a Checkout button that shows them their order
        // for review.
        if($(bindings.forward).length) {
            $(bindings.forward).click(function() {
                controller.onForward();
                return false;
            });
        }
        if($(bindings.back).length) {
            $(bindings.back).click(function() {
                controller.onBack();
                return false;
            });
        }
        /*
        if($(bindings.display).length) {
            $(bindings.display).click(function() {
                controller.onDisplayCart();
            });
        }
        if($(bindings.review).length) {
            $(bindings.review).click(function() {
                controller.onSubmitSelections();
            });
        }
        if($(bindings.billing).length) {
            $(bindings.billing).click(function() {
                controller.onReviewComplete();
            });
        }
        if($(bindings.finish).length) {
            $(bindings.finish).click(function() {
                controller.onBillingSubmit();
            });
        }
        if($(bindings.print).length) {
            $(bindings.print).click(function() {
                controller.onPrint();
            });
        }
        */
        // Bind the addProfile button so the super user
        // can add new profiles on the fly.
        if($(bindings.addProfile).length) {
            $(bindings.addProfile).click(function() {
                controller.addProfile();
                return false;
            });
        }
    };
    
    this.setTitle = function(title) {
        $('.page_title').html(title);
    };
    
    // This may get called if the user was at another step
    // and wanted to come back and edit the cart.
    this.renderDisplay = function() {
        if(this.view == 'display') {
            alert('called display on display');
            return;
        }
        for(var i in controller.attendeeLists) {
            controller.attendeeLists[i].render();
        }
        if(this.section != 'cart') {
            this.switchToCart();
        }
        this.setTitle(params.titles.cart);
        this.view = 'display';
        $(controller.bindings.edit).hide();
        $(controller.bindings.back).hide();
        if(params.display == 'multi') {
            $(controller.bindings.addProfile).show();
        }
    };
    
    // This gets called after selections are made
    // and the user is ready to checkout.
    this.renderReview = function() {
        if(this.view == 'review') {
            alert("called review on review");
            return;
        }
        if(this.section != 'cart') {
            this.switchToCart();
        }
        for(var i in controller.details) {
            var list = controller.attendeeLists[i];
            list.renderDetails(controller.details[i]);
        }
        
        this.setTitle(params.titles.review);
        this.view = 'review';
        
        $(controller.bindings.edit).show();
        $(controller.bindings.addProfile).hide();
        $(controller.bindings.back).show();
    };
    
    this.renderBilling = function() {
        if(this.view == 'billing') {
            alert('called billing on billing');
            return;
        }
        if(this.section != 'billing') {
            this.switchToBilling();
        }
        var billing = controller.billing;
        var row;
        $("#billing_totals").empty();
        var itemTotal = 0;
        for(var i in billing) {
            row = $("<tr></tr>");
            row.append("<td class='program_title' width='50%'>" + billing[i].programTitle + "</td>");
            row.append("<td width='25%'>" + billing[i].numAttendees + " @ " + billing[i].price + "ea.</td>");
            row.append("<td width='25%' align='right'>$" + billing[i].subTotal + "</td>");
            $("#billing_totals").append(row);
            itemTotal += billing[i].subTotal;
        }
        
        $(controller.bindings.billContainer + " #item_total").html('$' + itemTotal);
        
        this.view = 'billing';
        this.setTitle(params.titles.billing);
        $(controller.bindings.back).show();
    };
    
    this.renderFinish = function(message) {
        if(this.view == 'finish') {
            alert("called finish on finish");
            return;
        }
        
        $('#credit_card_box').html(message);
        $(controller.bindings.back).hide();
        $(controller.bindings.forward).hide();
        $(controller.bindings.print).show();
        this.setTitle(params.titles.finish);
        this.view = 'finish';
    };
    
    this.switchToCart = function() {
        $(controller.bindings.billContainer).hide();
        $(controller.bindings.container).show();
        this.section = 'cart';
    };
    
    this.switchToBilling = function() {
        $(controller.bindings.container).hide();
        $(controller.bindings.billContainer).show();
        this.section = 'billing';
    };
}

// Attendee Widget

function AttendeeList(selectionModel, element) {
    this.selectionModel = selectionModel;
    this.length = 1;
    this.element = element;
    
    this.bind = function() {
        var list = this;
        // Bind the onChange event
        $(element).change(function(event) {
            controller.onSelectAttendee(list, list.selectionModel, event);
        });
    };
    
    this.add = function() {
        // Dont allow the list to grow longer than the number of profiles 
        if(this.length < this.selectionModel.masterList.length) {
            this.length++;
        }
        this.render();
    };
    
    this.remove = function() {
        // Dont allow removing to take away the last box
        if(this.length > 1) {
            this.length--;
        }
        this.render();
    };
    
    this.render = function() {
        $(element).empty();
        var i;
        var j;
        var selectedList = this.selectionModel.selectedList;
        var unselectedList = this.selectionModel.unselectedList;
        var items;
        for(i = 0; i < this.length; i++) {
            
            items = $('<select></select>');
            var needRemove = false;
            // Select either an attendee from the selection model
            // or put up the default message option
            if(i < selectedList.length) {
                items.append('<option id="' + selectedList[i].id + '" value="Remove">Remove</option>');
                items.append('<option value="' + selectedList[i].id + '" selected="selected">' + selectedList[i].name + '</option>');
                needRemove = true;
            } else {
                items.append('<option value="" selected="selected">Add Attendee</option>');
            }
            
            // Then fill out the list of unselected
            for(j in unselectedList) {
                items.append('<option value="' + unselectedList[j].id + '">' + unselectedList[j].name + '</option>');
            }

            // Add the items to the container
            $(element).append(items);
            
            // If this box has an attendee provide a remove button
            if(needRemove) {
                var list = this;
                var remove = $('<div style="float:right; margin-right:30px;" id="' + selectedList[i].id + '">X</div>');
                $(element).append(remove);
                remove.click(function(event) {
                    controller.onRemoveAttendee(list, list.selectionModel, event.target.id);
                });
            }
        }
    };
    
    this.renderDetails = function(profiles) {
        // Render the selection model as text for review
        $(element).empty();
        for(var i in profiles) {
            var container = $("<div></div>");
            // TODO - Do a proper display
            // msg indicates an error or status message
            // not a user profile
            if(profiles[i].msg != undefined) {
                container.html("<h4>" + profiles[i].msg + "</h4>");
            } else {
                container.append("<h4 style='text-decoration:underline;'>" + profiles[i].name + "</h4>");
                container.append("<p class='cart_review'>");
                container.append([
                    profiles[i].address,
                    profiles[i].city + ', ' + profiles[i].state + ', ' + profiles[i].country,
                    profiles[i].zip
                ].join("<br />"));
                container.append("</p>");
            }
            $(element).append(container);
        }
    };
}

// SelectionModel Class //////////

function SelectionModel(profiles, spaces) {
    this.masterList = profiles;
    this.selectedList = new Array();
    this.unselectedList = profiles.slice();
    this.max = Math.min(profiles.length, spaces);
    
    this.select = function(id) {
        var i;
        for(i in this.unselectedList) {
            if(id == this.unselectedList[i].id) {
                var user = this.unselectedList.splice(i, 1);
                this.selectedList.push(user[0]);
                break;
            }
        }
    };
    
    this.unselect = function(id) {
        var i;
        for(i in this.selectedList) {
            if(id == this.selectedList[i].id) {
                var user = this.selectedList.splice(i, 1);
                this.unselectedList.push(user[0]);
                break;
            }
        }
    };
    
    this.add = function(id, name) {
        var item = {id: id, name: name};
        this.masterList.push(item);
        this.unselectedList.push(item);
    };
}

//////////////////////////////////