// topBar background vanishing when scrolling
$(document).scroll(function () {
    var isScrolled = $(this).scrollTop() > $(".topBar").height();
    $(".topBar").toggleClass("scrolled", isScrolled);
})

function volumeToggle(button) {
    var muted = $(".previewVideo").prop("muted");
    $(".previewVideo").prop("muted", !muted);

    // toggleClass: if the class exist -> remove it
    //              if the class not exist -> add it
    $(button).find("i").toggleClass("fa-volume-mute");
    $(button).find("i").toggleClass("fa-volume-up"); 
}

function previewEnded() {
    // turn the video off
    $(".previewVideo").toggle();
    // and turn the image on
    $(".previewImage").toggle();

}
function goBack() {
    window.history.back();
}
function startHideTimer() {
    var timeout = null;
    // when move mouse on the page, do the function()
    $(document).on("mousemove", function() {
        // if movemouse -> bar appear
        clearTimeout(timeout);
        $(".watchNav").fadeIn();
        
        // if no mousemove in 2 secs -> bar disappear
        timeout = setTimeout(function () {
            $(".watchNav").fadeOut();
        }, 2000);
    })
}
function initVideo(videoId, username) {
    startHideTimer();
    setStartTime(videoId, username);
    updateProgressTimer(videoId, username);
}
function updateProgressTimer(videoId, username) { 
    addDuration(videoId, username);
    var timer;
    $("video").on("playing", function(event) {
        window.clearInterval(timer);
        timer = window.setInterval(function() {
            updateProgress(videoId, username, event.target.currentTime);
        }, 3000);
    })
    .on("ended", function() {
        setFinished(videoId, username);
        window.clearInterval(timer);
    })
}
function addDuration(videoId, username) {
    // everything output in addDuration.php will be in the data variable
    $.post("ajax/addDuration.php", {videoId: videoId, username: username},function(data) {
        if(data !== null && data !== ""){
            alert(data);
        }
    })
}
function updateProgress(videoId, username, progress) {
    $.post("ajax/updateDuration.php", {videoId: videoId, username: username, progress: progress},function(data) {
        if(data !== null && data !== ""){
            alert(data);
        }
    })
}
function setFinished(videoId, username) {
    $.post("ajax/setFinished.php", {videoId: videoId, username: username},function(data) {
        if(data !== null && data !== ""){
            alert(data);
        }
    })
}
function setStartTime(videoId, username) {
    $.post("ajax/getProgress.php", {videoId: videoId, username: username},function(data) {
        if(isNaN(data)){
            alert(data);
            return;
        }
        $("video").on("canplay", function() {
            this.currentTime = data;
            $("video").off("canplay");
        })
    })
}
function restartVideo() {
    $("video")[0].currentTime = 0;
    $("video")[0].play();
    $(".upNext").fadeOut();
}
function watchVideo(videoId) {
    window.location.href = "watch.php?id=" + videoId;
}
function showUpNext() {
    $(".upNext").fadeIn();
}