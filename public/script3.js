/*
Copyright 2017 Google Inc.

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
*/

// This code is adapted from
// https://rawgit.com/Miguelao/demos/master/mediarecorder.html

// $("#retry").on("click", function(e) {
//     $("#record").css("display", "inline");
//     $("#retry").css("display", "none");
//     $("#gum").css("display", "inline");
//     $("#recorded").css("display", "none");
// });
var timeInterval;
var gumVideo = document.querySelector('video#gum');
var recordedVideo = document.querySelector('video#recorded');

var recordButton = document.querySelector('button#record');
var downloadButton = document.querySelector('button#download');
var recorededFile = false;
var playAllowed = true;

$("#record").on("click", function(e) { e.preventDefault() });

function timer() {
    var minute = 00;
    var sec = 01;
    timeInterval = setInterval(function() {
        if (minute < 10) {
            time = "0" + minute + " : " + sec;
            if (sec < 10)
                time = "0" + minute + " : 0" + sec;
        } else if (sec < 10)
            time = minute + " : 0" + sec;
        else
            time = minute + " : " + sec;
        document.getElementById("timer").innerHTML = time;
        sec++;
        if (sec == 59) {
            minute++;
        }
    }, 1000);
}

$("#question_answer_videoAnswer").on("click", function(e) {
    if (recorededFile) {
        e.preventDefault();
        $("#confirmationModal").modal("show");
    }
});

$("#question_answer_videoAnswer").change(function() {
    $("#playerToggle").css("display", "none");
});

$("#retry, #download, #cancel").on("click", function(e) {
    e.preventDefault();;
});

function startVideo() {
    var mediaSource = new MediaSource();
    mediaSource.addEventListener('sourceopen', handleSourceOpen, false);
    var mediaRecorder;
    var recordedBlobs;
    var sourceBuffer;

    recordButton.onclick = toggleRecording;
    downloadButton.onclick = download;

    console.log('location.host:', location.host);
    // window.isSecureContext could be used for Chrome
    // var isSecureOrigin = location.protocol === 'https:' ||
    //     location.host.includes('localhost');
    // if (!isSecureOrigin) {
    //     alert('getUserMedia() must be run from a secure origin: HTTPS or localhost.' +
    //         '\n\nChanging protocol to HTTPS');
    //     location.protocol = 'HTTPS';
    // }

    var constraints = {
        audio: true,
        video: true
    };

    navigator.mediaDevices.getUserMedia(
        constraints
    ).then(
        successCallback,
        errorCallback
    );
}

function successCallback(stream) {
    window.stream = stream;
    gumVideo.srcObject = stream;
}

function errorCallback(error) {
    console.log('navigator.getUserMedia error: ', error);
}

function handleSourceOpen(event) {
    // console.log('MediaSource opened');
    sourceBuffer = mediaSource.addSourceBuffer('video/webm; codecs="vp8"');
    console.log('Source buffer: ', sourceBuffer);
}

function handleDataAvailable(event) {
    if (event.data && event.data.size > 0) {
        recordedBlobs.push(event.data);
    }
}

function handleStop(event) {
    console.log("stopped");
}

function toggleRecording() {
    if (recordButton.textContent === 'Start Recording') {
        startRecording();
    } else {
        stopRecording();
        recordButton.textContent = 'Start Recording';
        downloadButton.disabled = false;
    }
}

// The nested try blocks will be simplified when Chrome 47 moves to Stable
function startRecording() {
    recorededFile = true;
    var options = { mimeType: 'video/webm;codecs=vp9', bitsPerSecond: 100000 };
    recordedBlobs = [];
    try {
        mediaRecorder = new MediaRecorder(window.stream, options);
    } catch (e0) {
        console.log('Unable to create MediaRecorder with options Object: ', options, e0);
        try {
            options = { mimeType: 'video/webm;codecs=vp8', bitsPerSecond: 100000 };
            mediaRecorder = new MediaRecorder(window.stream, options);
        } catch (e1) {
            console.log('Unable to create MediaRecorder with options Object: ', options, e1);
            try {
                mediaRecorder = new MediaRecorder(window.stream);
            } catch (e2) {
                alert('MediaRecorder is not supported by this browser.');
                console.log('Unable to create MediaRecorder', e2);
                return;
            }
        }
    }

    recordButton.textContent = 'Stop Recording';
    downloadButton.disabled = true;
    mediaRecorder.onstop = handleStop;
    mediaRecorder.ondataavailable = handleDataAvailable;
    mediaRecorder.start(10); // collect 10ms of data
    $("#recording-icon").css("display", "inline");
    timer();
}

function stopRecording() {
    recorededFile = true;
    playAllowed = true;
    clearInterval(timeInterval);
    $("#recording-icon").css("display", "none");
    $("#timer").html("00:00")
    $("#recorderModal").modal("hide");
    $("#recordToggle").attr("disabled", "disabled");
    $("#playerToggle").css("display", "inline");

    //stop mediaRecorder and MediaStream
    window.stream.getTracks() // get all tracks from the MediaStream
        .forEach(track => track.stop()); // stop each of them
    window.stream.getVideoTracks()[0].stop();

    //add to file input
    var type = (recordedBlobs[0] || {}).type;
    var superBuffer = new Blob(recordedBlobs, { type });
    let file = new File([superBuffer], "record.webm", { type: "video/webm", lastModified: new Date().getTime() });
    let container = new DataTransfer();
    container.items.add(file);
    fileInput = document.getElementById("question_answer_videoAnswer");
    fileInput.files = container.files;
    console.log(fileInput.files);

    recordedVideo.src = window.URL.createObjectURL(superBuffer);
    recordedVideo.controls = true;

}

function play() {
    if (playAllowed) {
        $("#download").css('display', 'inline');
        $("#retry").css("display", "inline");
        var type = (recordedBlobs[0] || {}).type;
        var superBuffer = new Blob(recordedBlobs, { type });
        recordedVideo.src = window.URL.createObjectURL(superBuffer);
    }
}

function download() {
    var blob = new Blob(recordedBlobs, { type: 'video/mp4' });
    // var fd = new FormData();
    // fd.append('data', blob);
    // fd.append('filename', "file.txt");

    // var xhr = new XMLHttpRequest();
    // xhr.open('POST', '/upload');
    // xhr.send(fd);

    // fetch("/upload", {
    //         method: "POST",
    //         body: blob
    //     })
    //     .then(response => {
    //         if (response.ok) return response;
    //         else throw Error(`Server returned ${response.status}: ${response.statusText}`)
    //     })
    //     .then(response => console.log(response.text()))
    //     .catch(err => {
    //         alert(err);
    //     });
    var url = window.URL.createObjectURL(blob);

    var a = document.createElement('a');
    a.style.display = 'none';
    a.href = url;
    var today = new Date();
    var date = today.getDate() + "" + today.getMonth() + "" + today.getFullYear();
    a.download = 'lms-record' + date + '.mp4 ';
    document.body.appendChild(a);

    console.log(a);
    a.click();
    setTimeout(function() {
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
    }, 100);
}


function closeModal() {
    stream.getTracks() // get all tracks from the MediaStream
        .forEach(track => track.stop()); // stop each of them
    stream.getVideoTracks()[0].stop();
    $("#recording-icon").css("display", "none");
    $("#timer").html("00:00")
    $("#recorderModal").modal("hide");
    recordButton.textContent = 'Start Recording';
    clearInterval(timeInterval);
}

function selectFromFile() {
    recorededFile = false;
    $("#confirmationModal").modal("hide");
    $('#question_answer_videoAnswer').trigger("click");
}

$("#ExistingVideoToggle").on("click", function() {
    video = $(this).attr("data");
    recorded.src = "/uploads/videos/" + video;
    $("#download").css('display', 'none');
    $("#retry").css("display", "none");
});

function removeRecorded() {
    player = document.getElementById("recorded");
    player.pause();
    player.currentTime = 0;
    $("#recorded").removeAttr("src");
    $("#player").modal("hide");
    playAllowed = false;
    $("#playerToggle").css("display", "none");
    $("#question_answer_videoAnswer").val(null);
};