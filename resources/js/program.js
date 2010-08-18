$(document).ready(function() {
    initSearch();
    bindSearch();
});

var titles = new Array();
var types = new Array();

function handleSearchboxToggle() {
    $("div#search_box").toggleClass("hide");
    $("div#search_open").toggleClass("hide");
}

function handleTitleSelect(element) {
    $("table#items > tbody > tr").hide();
    var text = $(element).text();
    $("table#items > tbody > tr").find(":first").each(function() {
        if($(this).text() == text) {
            $(this).parent().show();
        }
    });
}

function handleProgramTypeSelect() {
    var selected = $("select#search_type option:selected").val();
    $("table#items > tbody > tr").show();
    if(selected != '') {
        $("table#items > tbody > tr[types!=" + selected + "]").hide();
    }    
    $(document).height($("body").height());
}

function bindSearch() {
    // Bind the window handlers
    $("div#search_open").click(handleSearchboxToggle);
    $("div#search_close").click(handleSearchboxToggle);
    
    // Bind type select
    $("select#search_type").change(handleProgramTypeSelect);
}

function initSearch() {
    var rows = $('table#items > tbody > tr');
    // Setup the title search with autocomplete
    $(rows).find(":first").each(function() {
        var text = $(this).text();
        titles.push(text);
    });
    $("#search_title").autocompleteArray(titles, {
        autoFill: true,
        delay: 10,
        width: 450,
        onItemSelect: handleTitleSelect
    });
    
    // Setup the search select box
    var select = $("select#search_type");
    var values = new Array();
    $(rows).find(":nth-child(2)").each(function() {
        var text = $(this).text();
        var i = $.inArray(text, values);
        if(!(i + 1)) {
            values.push(text);
            i = values.length - 1;
            select.append("<option value=" + i + ">" + values[i] + "</option>");
        }
        $(this).parent().attr('types', i);
    });

    // Setup a date picker bounds
    // Setup per state selection
    // Setup price bounds
    
}
