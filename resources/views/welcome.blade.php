<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{config('app.name')}}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="container" x-data="alpine()">
<div class="row col mt-2">
    <h1 class="font-weight-bolder border-bottom" style="color: #db2777">{{config('app.name')}}</h1>

    <div class="col-12">
        <p class="font-weight-bold">Write down your vocabulary below and enjoy the dictation 😉</p>

        <form method="post">
            @csrf

            <div class="form-group">
                <label for="vocabulary" class="text-primary">
                    Vocabularies:
                    <small class="text-muted">(each line <strong>one</strong> word)</small>
                </label>

                <textarea id="vocabulary" name="vocabulary" class="form-control" rows="10" x-model.debounce="words"></textarea>

                <div class="row col">
                    @error('vocabulary')
                    <small class="text-danger">{{$message}}</small>
                    @endif

                    <small class="font-italic ml-auto" x-text="wordCount+' words'"></small>
                </div>
            </div>

            <button class="btn btn-success px-5">Submit</button>
        </form>

        <template x-if="vocabularies.length>0">
            <div class="row mx-auto col mt-3 pt-2 border-top">
                <div class="col-12">
                    <h3 x-text="'Found '+vocabularies.length +' words' "></h3>
                </div>

                <div class="row mx-auto col-12 border-bottom mb-2">
                    <div class="form-group">
                        <label for="repeat">Repeat:</label>
                        <input type="number" class="form-control" id="repeat" x-model.number="repeat" min="1">
                    </div>

                    <div class="form-group mx-0 mx-md-2">
                        <label for="repeat">Delay <small>(s)</small>:</label>
                        <input type="number" class="form-control" id="repeat" x-model.number="delay" min="1" step="0.1">
                    </div>

                    <div class="m-auto">
                        <div>
                            <label>
                                British
                                <input type="radio" name="language" x-model="language" value="bre">
                            </label>

                            <label class="mx-0 mx-md-2">
                                American
                                <input type="radio" name="language" x-model="language" value="ame">
                            </label>
                        </div>

                        <div class="text-center">
                            <button class="btn btn-outline-success mx-auto" @click="playAll">Play All</button>
                        </div>
                    </div>
                </div>

                <template x-for="vocabulary in vocabularies">
                    <div class="col-md-4 m-0 p-0">
                        <div class="border m-1">
                            <div class="row col-12 text-center p-3">
                                <strong x-text="vocabulary.word" class="ucfirst mx-auto"></strong>
                            </div>

                            <div class="row justify-content-around text-center">
                                <div @click="play(vocabulary.bre)" class="pointer">
                                    <svg class="svg-icon" viewBox="0 0 20 20" fill="red">
                                        <path
                                            d="M17.969,10c0,1.707-0.5,3.366-1.446,4.802c-0.076,0.115-0.203,0.179-0.333,0.179c-0.075,0-0.151-0.022-0.219-0.065c-0.184-0.122-0.233-0.369-0.113-0.553c0.86-1.302,1.314-2.812,1.314-4.362s-0.454-3.058-1.314-4.363c-0.12-0.183-0.07-0.43,0.113-0.552c0.186-0.12,0.432-0.07,0.552,0.114C17.469,6.633,17.969,8.293,17.969,10 M15.938,10c0,1.165-0.305,2.319-0.88,3.339c-0.074,0.129-0.21,0.201-0.347,0.201c-0.068,0-0.134-0.016-0.197-0.052c-0.191-0.107-0.259-0.351-0.149-0.542c0.508-0.9,0.776-1.918,0.776-2.946c0-1.028-0.269-2.046-0.776-2.946c-0.109-0.191-0.042-0.434,0.149-0.542c0.193-0.109,0.436-0.042,0.544,0.149C15.634,7.681,15.938,8.834,15.938,10 M13.91,10c0,0.629-0.119,1.237-0.354,1.811c-0.063,0.153-0.211,0.247-0.368,0.247c-0.05,0-0.102-0.01-0.151-0.029c-0.203-0.084-0.301-0.317-0.217-0.521c0.194-0.476,0.294-0.984,0.294-1.508s-0.1-1.032-0.294-1.508c-0.084-0.203,0.014-0.437,0.217-0.52c0.203-0.084,0.437,0.014,0.52,0.217C13.791,8.763,13.91,9.373,13.91,10 M11.594,3.227v13.546c0,0.161-0.098,0.307-0.245,0.368c-0.05,0.021-0.102,0.03-0.153,0.03c-0.104,0-0.205-0.04-0.281-0.117l-3.669-3.668H2.43c-0.219,0-0.398-0.18-0.398-0.398V7.012c0-0.219,0.179-0.398,0.398-0.398h4.815l3.669-3.668c0.114-0.115,0.285-0.149,0.435-0.087C11.496,2.92,11.594,3.065,11.594,3.227 M7.012,7.41H2.828v5.18h4.184V7.41z M10.797,4.189L7.809,7.177v5.646l2.988,2.988V4.189z"></path>
                                    </svg>

                                    <p class="font-italic">British</p>
                                </div>

                                <div @click="play(vocabulary.ame)" class="pointer">
                                    <svg class="svg-icon" viewBox="0 0 20 20" fill="blue">
                                        <path
                                            d="M17.969,10c0,1.707-0.5,3.366-1.446,4.802c-0.076,0.115-0.203,0.179-0.333,0.179c-0.075,0-0.151-0.022-0.219-0.065c-0.184-0.122-0.233-0.369-0.113-0.553c0.86-1.302,1.314-2.812,1.314-4.362s-0.454-3.058-1.314-4.363c-0.12-0.183-0.07-0.43,0.113-0.552c0.186-0.12,0.432-0.07,0.552,0.114C17.469,6.633,17.969,8.293,17.969,10 M15.938,10c0,1.165-0.305,2.319-0.88,3.339c-0.074,0.129-0.21,0.201-0.347,0.201c-0.068,0-0.134-0.016-0.197-0.052c-0.191-0.107-0.259-0.351-0.149-0.542c0.508-0.9,0.776-1.918,0.776-2.946c0-1.028-0.269-2.046-0.776-2.946c-0.109-0.191-0.042-0.434,0.149-0.542c0.193-0.109,0.436-0.042,0.544,0.149C15.634,7.681,15.938,8.834,15.938,10 M13.91,10c0,0.629-0.119,1.237-0.354,1.811c-0.063,0.153-0.211,0.247-0.368,0.247c-0.05,0-0.102-0.01-0.151-0.029c-0.203-0.084-0.301-0.317-0.217-0.521c0.194-0.476,0.294-0.984,0.294-1.508s-0.1-1.032-0.294-1.508c-0.084-0.203,0.014-0.437,0.217-0.52c0.203-0.084,0.437,0.014,0.52,0.217C13.791,8.763,13.91,9.373,13.91,10 M11.594,3.227v13.546c0,0.161-0.098,0.307-0.245,0.368c-0.05,0.021-0.102,0.03-0.153,0.03c-0.104,0-0.205-0.04-0.281-0.117l-3.669-3.668H2.43c-0.219,0-0.398-0.18-0.398-0.398V7.012c0-0.219,0.179-0.398,0.398-0.398h4.815l3.669-3.668c0.114-0.115,0.285-0.149,0.435-0.087C11.496,2.92,11.594,3.065,11.594,3.227 M7.012,7.41H2.828v5.18h4.184V7.41z M10.797,4.189L7.809,7.177v5.646l2.988,2.988V4.189z"></path>
                                    </svg>

                                    <p class="font-italic">American</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </template>
            </div>
        </template>
    </div>
</div>
<script>
    window.addEventListener('load', function () {
        Alpine.start();
    });

    const alpine = () => ({
        repeat: 1,
        delay: 1,
        wordCount: 0,
        words: '',
        language: 'ame',
        vocabularies:@json(@$vocabularies ?? []),
        init() {
            this.$watch('words', (val) => {
                const words = val.trim().split('\n');
                this.wordCount = words.length;
                this.words = words.map(x => x.trim()).join('\n');
            })
        },
        async asyncPlay(url) {
            return new Promise((resolve, reject) => {
                audio.src = url;

                audio.play();

                audio.onerror = () => {
                    reject();
                };

                audio.onended = async () => {
                    resolve();
                };
            });
        },
        async play(url, count = 0) {
            await this.asyncPlay(url).then(async () => {
                count++;
                await new Promise(resolve => setTimeout(resolve, this.delay * 1000));

                if (count < this.repeat)
                    await this.play(url, count);
            }).catch(() => {
            });
        },
        async playAll() {
            for (let item of this.vocabularies)
                await this.play(item[this.language]).catch(() => {
                })
        }
    });
</script>
</body>
</html>
