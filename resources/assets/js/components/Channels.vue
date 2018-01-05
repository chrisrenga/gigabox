<template>
    <div class="container-fluid" style="padding-top: 110px; padding-bottom: 60px;">

        <div 
            class="flex flex-col fixed shadow"
            style="padding: 15px; background-color: #fff; top: 0; left: 0; width: 100%;" 
        >
            <div style="margin-bottom: 10px;">
                Stai Guardando: <strong>{{ current.e2servicename }}</strong>
            </div>
            <div class="flex justify-between align-center">
                <div style="position: absolute; margin: 10px 0 0 13px;"><i class="fa fa-search"></i></div>
                <input 
                    v-model="search"
                    type="text" 
                    class="search form-control" 
                    style="padding-left: 40px;" 
                    placeholder="Cerca..."
                >
                <button
                    v-if="search.length"
                    @click.prevent="search = ''"
                    class="absolute btn btn-link"
                    style="right: 20px; top: 50px;"
                    type="button"
                >X</button>                
            </div>

        </div>

        <div v-if="loading" class="text-center">
            <br><br>
            <i class="fa fa-cog fa-spin fa-2x"></i>
            <br>Loading...
        </div>
        <div
            v-else
        >
            <div
                v-for="(channel, index) in channels"
                :key="index"
                :class="{'is-category': isCategory(channel.e2servicename)}"
                class="channel"
                :id="idfy(channel.e2servicename)"
            >

                <strong>{{ channel.e2servicename }}</strong>
                
                <div v-if="!isCategory(channel.e2servicename)">   
                    <button 
                        @click.prevent="toggleTrailer(channel)"
                        class="btn btn-link"
                        type="button" 
                    > <i class="fa fa-video-camera"></i> Trailer</button>

                    <button
                        @click.prevent="play(channel)"
                        class="btn btn-link"
                        type="button" 
                    > <i class="fa fa-play"></i> Guarda ora</button>
                </div>
            </div>
        </div>

        <div class="controls shadow flex justify-between">
            <button
                @click.prevent="togglePower"
                type="button"
                class="btn btn-primary"
            >
                <i class="fa fa-power-off"></i>
            </button>

            <button
                @click.prevent="toggleCategories"
                type="button"
                class="btn btn-default"
            > <i class="fa fa-tags"></i> Categorie</button>

            <button
                @click.prevent="reload"
                class="btn btn-link"
            > <i class="fa fa-repeat"></i> Ricarica Lista canali</button>
        </div>

        <div v-if="showTrailer" class="trailer__modal">
            <div class="trailer__modal__title">
                <button 
                    @click.prevent="toggleTrailer"
                    class="btn btn-link"
                    type="button" 
                >
                    Chiudi
                </button>
            </div>
            <div
                v-for="(trailer, index) in trailers"
                :key="index"
                class="flex justify-center"
                style="margin-bottom: 15px;"
            >
                <iframe width="560" height="315"
                    :src="'https://www.youtube.com/embed/' + trailer.id.videoId" 
                    frameborder="0" 
                    gesture="media" 
                    allow="encrypted-media" 
                    allowfullscreen
                ></iframe>
            </div>
            <div class="trailer__modal__footer">
                <strong>{{ trailerForChannel.e2servicename }}</strong>

                <button
                    @click.prevent="play(trailerForChannel)"
                    type="button"
                    class="btn btn-success"
                >Guarda ora</button>
            </div>
        </div>

        <div v-if="showCategories" class="categories__modal">
            <div class="trailer__modal__title" style="top: -20px;">
                <button 
                    @click.prevent="toggleCategories"
                    class="btn btn-link"
                    type="button" 
                >
                    Chiudi
                </button>
            </div>
            <div class="categories__modal__body flex flex-wrap">
                <a
                    v-for="(category, index) in categories"
                    :key="index"
                    :href="'#' + idfy(category.id)"
                    @click="toggleCategories"
                    class="category__btn btn btn-default flex-1 shadow"
                    v-text="category.name"
                ></a>
            </div>
        </div>

    </div>
</template>
<script>
    export default {
        data() {
            return {
                loading: false,
                search: '',
                powerStatus: '',
                current: [],
                loadedChannels: [],
                showTrailer: false,
                showCategories: false,
                trailerForChannel: [],
                trailers: [],
                categories: [
                    {
                        name: 'Vod IT HD',
                        id: 'vod HD',
                    },
                    {
                        name: 'VOD 3D',
                        id: 'VOD 3D'
                    },   
                    {
                        name: 'VOD cult IT',
                        id: 'VOD CULT'
                    },
                    {
                        name: 'VOD - VIDEOTECA',
                        id: 'VOD - VIDEOTECA'                    
                    },
                    {
                        name: 'PRIMAFILA',
                        id: 'PRIMAFILA'
                    },     
                    {
                        name: 'Cinema IT',
                        id: 'CINEMA'
                    },
                    {
                        name: 'Sky IT',
                        id: 'SKY ITALIA'
                    },                                                         
                    {
                        name: 'Vod DE DVD',
                        id: 'Vod DvdRip DE'
                    },      
                    {
                        name: 'Sky DE',
                        id: 'SKY DE'
                    },     
                    {
                        name: 'Tv DE', 
                        id: 'DEUTSCHE'
                    },
                    {
                        name: 'VOD DE',
                        id: 'DEUTSCHE VOD'
                    },
                    {
                        name: 'Sky UK',
                        id: 'Sky UK Movies'
                    }
                ],
            }
        },

        computed: {

            channels()
            {
                return this.loadedChannels.filter((cust) => {
                    return cust.e2servicename.toLowerCase().indexOf(this.search.toLowerCase())>=0;
                });
            }

        },

        mounted() {
            this.loading = true;
            this.render();
        },

        methods: {
            isCategory(name) {
                var iscat = name.includes('##') || name.includes('--');
                return iscat;
            },

            idfy(name) {
                name = name.toLowerCase();
                name = name.trim();
                name = _.replace(name, new RegExp("#", "g"), "");
                name = name.trim();
                name = _.replace(name, new RegExp("-", "g"), "");
                name = name.trim();
                name = _.replace(name, new RegExp(" ", "g"), "-");
                name = name.trim();
                return name;  
            },
            render() {
                axios.get('/api/channels')
                    .then(response => {
                        this.loadedChannels = response.data.channels;
                        this.current = response.data.current;
                        this.loading = false;
                    });
            },

            reload() {
                this.loading = true;
                axios.post('/api/channels')
                    .then(response => {
                        flash('re-loaded');
                        this.loading = false;
                    });
            },

            play(channel) {
                var ch = channel.e2servicereference;
                var id = ch.substr(0, ch.indexOf('http')); 
                var command = 'api/zap?sRef=' + escape(ch);
                axios.patch('/api/channels/1', {command})
                    .then(response => {
                        flash('canale cambiato');
                    });
            },

            togglePower() {
                // 0 = Toogle Standby
                // 1 = Deepstandby
                // 2 = Reboot
                // 3 = Restart Enigma2
                // 4 = Wakeup form Standby
                // 5 = Standby
                var command = 'api/powerstate?newstate=0';
                axios.patch('/api/channels/1', {command})
            },

            toggleTrailer(video) {
                this.showTrailer = !this.showTrailer;
                if (this.showTrailer) {
                    this.searchTrailers(video);
                }
            },

            searchTrailers(video) {
                this.trailerForChannel = video;
                axios.get('/api/trailers?q=' + video.e2servicename )
                    .then(response => {
                        this.trailers = response.data;
                    })
            },

            toggleCategories() {
                this.showCategories = !this.showCategories;
            },            
        }
    }
</script>

<style>

.controls {
    position: fixed;
    bottom: 0;
    left: 0;
    background-color: #f4f4f4;
    width: 100%;
    padding: 15px;
    border-top: 1px solid #cacaca;
}

.search {
    font-size: 18px;
    padding: 20px;
}

.channel {
    padding: 10px;
    margin: 5px 0;
    display: flex;
    justify-content: space-between;
}

.channel.is-category {
    text-align: center !important;
    font-size: 20px;
    background-color: #cacaca;
}

.trailer__modal, .categories__modal {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    width: 100%;
    height: 100%;
    padding: 20px;
    overflow: scroll;
    background-color: rgba(255, 255, 255, 0.8);
}

.trailer__modal__title {
    position: fixed;
    top: 0;
    width: 100%;
    left: 0;
    right: 0;
    text-align: right;
    padding-top: 30px;
}

.trailer__modal__footer {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    width: 100%;
    padding: 20px;
    background-color: #fff;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.categories__modal__body {
    padding-top: 40px;
}

.category__btn {
    padding: 40px;
    font-size: 16px;
    margin: 15px;
}
</style>
