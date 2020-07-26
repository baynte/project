<template>
    <div id="videoContainer" class="d-flex justify-center" style="position: relative">
       <video id="video" height="560" width="720" autoplay muted></video> 
    </div>
</template>

<script>

export default {
    async created(){
        console.table(faceapi.nets)

        await faceapi.nets.tinyFaceDetector.loadFromUri("../models");
        await faceapi.nets.faceLandmark68Net.loadFromUri("../models");
        await faceapi.nets.faceRecognitionNet.loadFromUri("../models");
        await faceapi.nets.faceExpressionNet.loadFromUri("../models");
        await faceapi.nets.ageGenderNet.loadFromUri("../models");
        
        let video = document.getElementById("video");
        let canv = document.getElementById("canv");
        let predictedAges = [];

        navigator.getUserMedia(
            { video: {} },
            stream => (video.srcObject = stream),
            err => console.error(err)
        )

        video.addEventListener('play', () =>{
            const canvas = faceapi.createCanvasFromMedia(video)

            document.getElementById("videoContainer").append(canvas)

            const displaySize = { width: video.width, height: video.height }

            faceapi.matchDimensions(canvas, displaySize)
            setInterval( async () => {
                const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions())
                .withFaceExpressions()

                //console.log(detections);
                canvas.getContext('2d').clearRect( 0, 0, canvas.width, canvas.height)
                const resizedDetections = faceapi.resizeResults(detections, displaySize)
                faceapi.draw.drawDetections(canvas, resizedDetections)
            }, 100)
        })
    }
}
</script>

<style>
    canvas{
        position: absolute
    }
</style>