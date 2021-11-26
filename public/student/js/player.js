var selected_doc = 0;
$("#startQuiz").on('click', function(e) {
    e.preventDefault()
    if (parseInt(control.view_at) == control.content_id.length && parseInt(control.view_at) != 0) {
        window.location = $(this).attr("href");
    } else {
        console.log("fail");
    }
});

$("li.content-list").on("click", function() {
    $("li.content-list").removeClass("active");
    $(this).addClass("active");
    lists = $(".list li");
    var id = $(this).attr("data-type");
    content = $(this).attr("content");
    changePlayerContent(lists, id, content);
});

$("#content-collpse").on("click", function(e) {
    if ($("#content-collapse-icon").hasClass("fa-chevron-down")) {
        $("#content-collapse-icon").removeClass('fa-chevron-down');
        $("#content-collapse-icon").addClass('fa-chevron-right');
    } else {
        $("#content-collapse-icon").removeClass('fa-chevron-right');
        $("#content-collapse-icon").addClass('fa-chevron-down');
    }
});

function changePlayerContent(lists, id, content) {
    if ((lists.length - 1) == control.num_of_list && parseInt(control.view_at) >= control.content_id.indexOf(parseInt(id))) {
        $("#intro").remove();

        /* if(content === "doc")
         {
             if(selected_doc != id){
                 $("#youtube-link").css("display", "none");
                 $("#youtube-link").attr("src","");
                 $("#video").html("");
                 $("#text-card").css("display","block");
                 url = "/content/"+id+"/cont";
                 
                 $.ajax({
                     url: url,
                     type: "get",
                     success: function(data)
                     {
                         if(xhr.status == 200)
                         {
                             $("#html-text").html(xhr.responseText);
                         }
                     },
                     error: function(xhr)
                     {
                         if(xhr.status == 200)
                         {
                             $("#html-text").html(xhr.responseText);
                         }
                     }
                 });
                 selected_doc = id;
             }

         }*/
        if (content === "youtube") {
            //	var iframe = $('<iframe height="453" width="100%" src="" id="youtube-link" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');
            //	$("#youtube").append(iframe);
            selected_doc = 0;
            $("#text-card").css("display", "none");
            $("#video").html("");
            $("#youtube-link").css("display", "block");
            $("#youtube-link").attr("src", videos[id]);
            getContentAndFiles(id);
        } else if (content === "filename") {
            $("#video").html("");
            videoHtml = $("#video").html();
            if (videoHtml == "") {
                var videoSkin = $("<video controls width='100%' height='453'></video>");
                var source = $("<source id='video-source' src=''>");
                videoSkin.append(source);
            }
            $("#video").append(videoSkin);
            $("#youtube-link").css("display", "none");
            $("#youtube-link").attr("src", "");
            $("#text-card").css("display", "none");
            $("#video").css("display", "block");
            $("#video-source").attr("src", "/uploads/resources/" + videos[id]);
            getContentAndFiles(id);
        } else {
            selected_doc = 0;
            $("#video").html("");
            $("#youtube-link").css("display", "none");
            $("#youtube-link").attr("src", "");
            getContentAndFiles(id);
        }

        $("input[name=" + id + "]").attr("checked", "checked");

        if (!control.viewed_content.includes(parseInt(id)) && control.content_id.includes(parseInt(id))) {
            updateCounter(id);
        }
    }
}

function getContentAndFiles(id) {
    $(".spinner-border").removeAttr("style");
    url = "/content/" + id + "/cont";
    $.ajax({
        url: url,
        type: "get",
        success: function(data) {
            if (data.content != null)
                $(".content").html(data.content);
            else
                $(".content").html("<span>No description</span>");

            if (data.resources != null) {
                $('.resource').html("");
                resourses = JSON.parse(data.resources);
                $.each(resourses, function(key, value) {
                    res = $("<li class='list-unstyled'><a href='/uploads/" + value + "' target='_blank' style='color:#0088cc'>" + value + " </a></li>");
                    $('.resource').append(res);
                });
            } else
                $(".resource").html("<span>No Resources given</span>");


            $(".spinner-border").css("display", "none");
        },
        error: function(xhr) {
            console.log("error");
        }
    });
}
$(document).ready(function() {
    activeList(parseInt(control.view_at), control.content_id[0]);
});


function activeList(num, id) {
    if (num != 0 && !control.viewed_content.includes(id)) {
        control.viewed_content.push(parseInt(id));
    }
    lists = $(".list li");
    var flag = 1;
    if ((lists.length - 1) == control.num_of_list) {
        lists.each(function() {
            if (flag == num + 1) {
                $(this).removeClass("disabled");
            }
            flag++;
        });
    } else {
        console.log("err");
    }

}