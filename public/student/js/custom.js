function responsive(node) {
    /* Storing user's device details in a variable*/
    let details = navigator.userAgent;
    /* Creating a regular expression 
    containing some mobile devices keywords 
    to search it in details string*/
    let regexp = /android|iphone|kindle|ipad/i;

    /* Using test() method to search regexp in details
    it returns boolean value*/
    let isMobileDevice = regexp.test(details);

    if (isMobileDevice) {
        $(node).addClass("mt-5");
    } else {
        console.log("web");
    }
}

function responsive1(node) {
    /* Storing user's device details in a variable*/
    let details = navigator.userAgent;
    let regexp = /android|iphone|kindle|ipad/i;

    let isMobileDevice = regexp.test(details);

    if (isMobileDevice) {} else {
        $(node).addClass("px-5");
    }
}

function isMobile() {
    let details = navigator.userAgent;
    let regexp = /android|iphone|kindle|ipad/i;
    let isMobileDevice = regexp.test(details);

    if (isMobileDevice)
        return true;
    else
        return false;
}

var resources = [];
var resources_main = [];
$("#content_resources").on("change", function() {
    var ele = document.getElementById($(this).attr('id'));
    var result = ele.files;
    $("#resource-list").html("");
    resources = [];
    resources_main = [];
    $.each(result, function(key, value) {
        resources.push(value.name);
        resources_main.push(value);
        list = $('<li class="list-group-item" list-num=' + key + '><i class="fa fa-times-circle" onclick="removeResource(\'' + value.name + '\',' + key + ')" style="color:red;padding:7px 20px 5px 20px;"></i> ' + value.name + '</li>');
        $("#resource-list").append(list);
    });
    console.log(result);
});