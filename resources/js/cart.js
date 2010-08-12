// This gets set to a value within the document.
var profiles;
var companyInfo;
var controller;
// TODO
// Need to update the page title on transitions
// Drop the add profile button when not on the cart page

if(window.location.pathname == '/cart/display') {
    $(document).ready(
        function() {
            // UI Controller
            controller = new Controller({
                login:      '#login',
                register:   '#register',
                review:     '#review',
                billing:    '#billing',
                finish:     '#finish',
                print:      '#print',
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
    
    this.go = function() {
        this.cart = new ShoppingCart();
        this.cart.bind(bindings);
    };
    
    this.addProfile = function() {
        $('#profile_box').overlay({
            top: 100,
            left: 100,
            mask: {
                color: '#fff',
                loadSpeed: 200,
                opacity: 0.5
            },
            closeOnClick: false,
            load: true
        });
    };
    
    this.addProgram = function(programId, element) {
        var model = new SelectionModel(profiles);
        var list = new AttendeeList(model, element);
        list.render();
        
        this.programs.push(programId);
        this.selectionModels.push(model);
        this.attendeeLists.push(list);
        
        return list;
    };
    
    this.onAddProfile = function() {
        // Display the profile form
        
        // Submit the profile
        var id, name;
        
        // Add the profile to the selection models
        for(var i in this.selectionModels) {
            this.selectionModels[i].add(id, name);
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
                    controller.onFinish();
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
    
    this.onFinish = function() {
        this.cart.renderFinish();
    };
    
    this.onPrint = function() {
        alert("Print Me!");
    };
    
    this.onReviewComplete = function() {
        this.cart.renderBilling();
    };
    
    this.onSelectAttendee = function(list, selectionModel, event) {
        var val = $(event.target).val();
        var lastVal = $(event.target).children(':nth-child(2)').val();
        if(val == '') {
            // User selected 'Remove'
            selectionModel.unselect(lastVal);
            list.remove();
        } else {
            // Could be an initial selection
            // or could be a selection change
            var text = $(event.target).children(':first').text();
            if(text == 'Remove') {
                selectionModel.unselect(lastVal);
                selectionModel.select(val);
                list.render();
            } else {
                selectionModel.select(val);
                list.add();
            }
        }
    };

    this.onSubmitSelections = function() {
        var i, j;
        var items = new Array();
        for(i in this.programs) {
            var programId = this.programs[i];
            var profiles = this.selectionModels[i].selectedList;
            if(profiles.length > 0) {
                items.push({ programId: programId , profiles: profiles});
            }
        }
        
        $.post('/cart/process', {json: JSON.stringify(items)}, function(data, status, xhr) {
            switch(xhr.status) {
                case 202 : // ACCEPTED
                    var json = JSON.parse(data);
                    controller.billing = json.billing;
                    controller.cart.renderReview(json.details);
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
}

// ShoppingCart Composite

function ShoppingCart() {
    this.bindings = null;
    this.container = null;
    this.view = 'display';
    
    this.bind = function(bindings) {
        this.bindings = bindings;
        
        // First we setup the cart container
        // and bind an AttendeeList widget to each
        // program and render the widget
        this.container = $(bindings.container);
        this.container.children().each(function() {
            var element = $(this).find('#attendees');
            controller.addProgram(this.id, element);
        });
        
        // Next we bind buttons. Login/Register buttons
        // bind to a page reload bringing the user to the
        // respective pages. An authenticated user will have
        // a Checkout button that shows them their order
        // for review.
        if($(bindings.login).length) {
            $(bindings.login).click(function() {
                // Make true for https
                doPageLoad('/account/login', false, true);
            });
        }
        if($(bindings.register).length) {
            $(bindings.register).click(function() {
                // Make true for https
                doPageLoad('/account/register', false, true);
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
        
        // Bind the addProfile button so the super user
        // can add new profiles on the fly.
        if($(bindings.addProfile).length) {
            $(bindings.addProfile).click(function() {
                controller.onAddProfile();
            });
        }
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
        this.view = 'display';
    };
    
    // This gets called after selections are made
    // and the user is ready to checkout.
    this.renderReview = function(details) {
        if(this.view == 'review') {
            alert("called review on review");
            return;
        }
        for(var i in details) {
            var list = controller.attendeeLists[i];
            list.renderDetails(details[i]);
        }
        this.view = 'review';
        
        // Change the buttons
        $("#review").hide();
        $("#billing").show();
    };
    
    this.renderBilling = function() {
        if(this.view == 'billing') {
            alert('called billing on billing');
            return;
        }
        $(controller.bindings.container).hide();
        $(controller.bindings.billContainer).show();
        
        // TODO - Have the server-side send the comapny
        //        info on load
        $("#company_info").empty();
        $("#company_info").append("<h4>Company Name</h4><br />");
        $("#company_info").append("<p>");
        $("#company_info").append([
            companyInfo.name,
            companyInfo.address,
            companyInfo.city + ", " + companyInfo.state + ", " + companyInfo.country,
            companyInfo.zip + "<br />",
            "P: " + companyInfo.phone,
            "F: " + companyInfo.fax
        ].join("<br />"));
        $("#company_info").append("</p>");
        
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
        $("#billing").hide();
        $("#finish").show();
    };
    
    this.renderFinish = function() {
        if(this.view == 'finish') {
            alert("called finish on finish");
            return;
        }
        $('#credit_card_box').hide();
        $("#finish").hide();
        $("#print").show();
        this.view = 'finish';
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
    
    this.bind();
    
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
            // Select either an attendee from the selection model
            // or put up the default message option
            if(i < selectedList.length) {
                items.append('<option id="' + selectedList[i].id + '" value="">Remove</option>');
                items.append('<option value="' + selectedList[i].id + '" selected="selected">' + selectedList[i].name + '</option>');
            } else {
                items.append('<option value="" selectd="selected">Add Attendee</option>');
            }
            
            // Then fill out the list of unselected
            for(j in unselectedList) {
                items.append('<option value="' + unselectedList[j].id + '">' + unselectedList[j].name + '</option>');
            }

            // Add the items to the container
            $(element).append(items);
        }
        
        // Stick an add button to the bottom to trigger a new attendee slot
        var button = $('<div class="add_attendee_button">Add Attendee</div>');
        var attendees = this;
        
        // FIXME Fix the logic with the boxes so this button can be removed.
        button.click(function() {
            if(attendees.length >= attendees.selectionModel.masterList.length) {
                alert('maxlength reached');
                return;
            }
            attendees.add();
        });
        $(element).append(button);
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
                container.append("<h4>" + profiles[i].name + "</h4>");
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

function SelectionModel(profiles) {
    this.masterList = profiles;
    this.selectedList = new Array();
    this.unselectedList = profiles.slice();
    
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