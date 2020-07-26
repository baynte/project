<template>
    <div id="videoContainer" class="d-flex justify-center" style="position: relative">
        <v-progress-circular
            indeterminate
            color="yellow"
            :size="300"
            :width="10"
            style="position: absolute; top: 200px"
            v-if="loading"
            ></v-progress-circular>
       <video id="video" class="p-0" height="560" width="720" autoplay muted></video>
    </div>
</template>

<script>

export default {
    data(){
        return{
            loading: true
        }
    },
    async created(){

        //console.table(faceapi.nets)

        await faceapi.nets.tinyFaceDetector.loadFromUri("../models");
        await faceapi.nets.faceLandmark68Net.loadFromUri("../models");
        await faceapi.nets.faceRecognitionNet.loadFromUri("../models");
        await faceapi.nets.faceExpressionNet.loadFromUri("../models");
        this.loading = false

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

                const resizedDetections = faceapi.resizeResults(detections, displaySize)
                
                canvas.getContext('2d').clearRect( 0, 0, canvas.width, canvas.height)

                faceapi.draw.drawDetections(canvas, resizedDetections)
                faceapi.draw.drawFaceExpressions(canvas, resizedDetections)
                
            }, 100)
        })
    }
}
</script>

<style>
    canvas{
        position: absolute
    }
    video {
        border: 2px solid pink;
    }
</style>