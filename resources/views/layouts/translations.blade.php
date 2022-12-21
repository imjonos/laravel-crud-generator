<script type="text/javascript">
    window.trans = @php
        $locale = App::getLocale();
        $defaultLocale = config('app.fallback_locale');
        $path = resource_path() . '/lang/';
        $localePath = $path . ((file_exists($path . $locale)) ? $locale : $defaultLocale);
        $defaultLocalePath = $path . $defaultLocale;

        $langFiles = File::files($localePath);
        $defaultLangFiles = File::files($defaultLocalePath);
        $trans = [];

        foreach ($defaultLangFiles AS $defaultLangFile){
            $defaultFilename = pathinfo($defaultLangFile)['filename'];
            $result = trans($defaultFilename, [], $defaultLocale);
            foreach ($langFiles as $file) {
                $filename = pathinfo($file)['filename'];
                if($defaultFilename === $filename && Lang::has($filename)) {
                     $result = trans($filename);
                }
            }

            $trans[$defaultFilename] = $result;
        }
        //пример подключения языков для других пакетов
        //$trans['coders-studio/chat']['chat'] = \Lang::get('coders-studio/chat::chat');
        echo json_encode($trans);
    @endphp;
</script>
