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