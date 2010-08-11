// This gets set to a value within the document.
var profiles;
var controller;

$(document).ready(
    function() {
        // UI Controller
        controller = new Controller();
        
        // Hookup the Add New Profile button
        $('#add_new_profile').click(function() {
            controller.addProfile();
        });
        
        // Hookup the Continue button
        $('#super_continue').click(function() {
            controller.submitSelections();
        });
        
        // Each program in the cart needs its own selection model
        // and attendee widget that interacts with the model.
        $('#super_cart_list > div').each(function() {
            var element = $(this).find("#attendees");
            var list = controller.addProgram(this.id, element);
            $(this).change(function(event) {
                list.onChange(event);
            });
        });
    }
);

// UI Controller
function Controller(add, submit) {
    
    this.programs        = new Array();
    this.selectionModels = new Array();
    this.attendeeLists   = new Array();
    
    this.addProfile = function() {
        alert('TODO - Add New Profile');
    };
    
    this.addProgram = function(programId, element) {
        //alert(element.id);
        var model = new SelectionModel(profiles);
        var list = new AttendeeList(model, element);
        list.render();
        
        this.programs.push(programId);
        this.selectionModels.push(model);
        
        return list;
    };
    
    this.submitSelections = function() {
        var i, j;
        var items = new Array();
        for(i in this.programs) {
            var programId = this.programs[i];
            var profiles = this.selectionModels[i].selectedList;
            if(profiles.length > 0) {
                items.push({ programId: programId , profiles: profiles});
            }
        }
        alert(JSON.stringify(items));
    };
}

// Attendee Widget

function AttendeeList(selectionModel, element) {
    this.selectionModel = selectionModel;
    this.length = 1;
    
    this.onChange = function(event) {
        var val = $(event.target).val();
        var lastVal = $(event.target).children(':nth-child(2)').val();
        if(val == '') {
            // User selected 'Remove'
            this.selectionModel.unselect(lastVal);
            this.remove();
        } else {
            // Could be an initial selection
            // or could be a selection change
            var text = $(event.target).children(':first').text();
            if(text == 'Remove') {
                this.selectionModel.unselect(lastVal);
                this.selectionModel.select(val);
                this.render();
            } else {
                this.selectionModel.select(val);
                this.add();
            }
        }
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
        button.click(function() {
            if(attendees.length >= attendees.selectionModel.masterList.length) {
                alert('maxlength reached');
                return;
            }
            attendees.add();
        });
        $(element).append(button);
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
        // TODO - Called when the user adds a new profile from this screen
    };
}

//////////////////////////////////