$(document).ready(function() {

    $('#course_id').change(function() {
        var courseId = $(this).val();

        if (courseId) {
            $.ajax({
                url: '/admin/courses/' + courseId + '/playlists',
                type: 'GET',
                success: function(data) {
                    var playlistDropdown = $('#playlist_id');
                    playlistDropdown.empty(); // Clear any existing options
                    playlistDropdown.append('<option value="">Select Your Playlist ...</option>'); // Default option

                    // Populate the playlist dropdown with new data
                    $.each(data, function(key, playlist) {
                        playlistDropdown.append('<option value="' + playlist.id + '">' + playlist.name + '</option>');
                    });
                },
                error: function() {
                    alert('Unable to fetch playlists');
                }
            });
        } else {
            $('#playlist_id').empty().append('<option value="">Select Your Playlist ...</option>');
        }
    });


    $('#video_input-file').on('change', function() {
        $('#video_upload-wrapper').css('display', 'none');
        $('#video_properties').css('display', 'block');

        var video = this.files[0];
        var videoName = video.name.split('.').slice(0, -1).join('.');
        var videoId = $(this).data('video-id');
        var url = $(this).data('url');

        $('#video_name').val(videoName);

        var formData = new FormData();
        formData.append('video_id', videoId);
        formData.append('name', videoName);
        formData.append('video', video);
        console.log(video);
        console.log(url);
        $.ajax({
            url: url,
            data: formData,
            method: 'POST',
            processData: false,
            contentType: false,
            cache: false,

            success: function (videoBeforeProcessing) {

                var interval = setInterval(function () {

                    $.ajax({
                        url: `/admin/videos/${videoBeforeProcessing.id}`,
                        method: 'GET',
                        success: function (videoWhileProcessing) {

                            $('#video_upload-status').html('Processing');
                            $('#video_upload-progress').css('width', videoWhileProcessing.percent + '%');
                            $('#video_upload-progress').html(videoWhileProcessing.percent + '%');

                            if (videoWhileProcessing.percent == 100) {
                                clearInterval(interval); //break interval
                                $('#video_upload-status').html('Done Processing');
                                $('#video_upload-progress').parent().css('display', 'none');
                                $('#video_submit-btn').css('display', 'block');
                            }
                        },
                    });//end of ajax call

                }, 3000)

            },
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener('progress', function(evt) {
                    if(evt.lengthComputable) {
                        var percentComplete = Math.round(evt.loaded / evt.total * 100) + "%";
                        $('#video_upload-progress').css('width', percentComplete).html(percentComplete);
                    }
                }, false);
                return xhr;
            }
        })
    });
});
