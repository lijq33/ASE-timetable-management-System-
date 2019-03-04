<template>
    <div>
        <div class = "tw-py-2 xl:tw-pb-16" >
            <!-- <image-gallery></image-gallery> -->
        </div>

        <div class = "
            tw-flex-col
            xl:tw-flex-row xl:tw-mx-16 xl:tw-py-16
            tw-flex tw-justify-between">
            <div class = "
                tw-w-full
                xl:tw-w-2/5 xl:tw--ml-16 
                tw-flex tw-justify-center">
                <div class = "tw-flex tw-flex-col">
                    <div class ="
                        tw-text-xl
                        xl:tw-text-3xl 
                        tw-font-bold tw-py-8 tw-text-center">
                        Popular Companies <i class="fas fa-fire"></i>
                    </div>
                    <button-group></button-group>
                </div>
            </div>

        </div>

    </div>
</template>

<script>

    import ButtonGroup from './../components/ButtonGroup';
    // import ImageGallery from './../components/ImageGallery';

    export default {
         components:{
            'button-group' : ButtonGroup,
    //         'image-gallery': ImageGallery
        },
                       
    }
</script>
