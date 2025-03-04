<x-app-layout>

    <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0" style="height: calc(100vh - 4rem);">
        <main class="flex items-center justify-center" style="flex-direction:column;">
            <div>Университет Синергия</div>
            <div>Практическая работа по кейс-заданию №5</div>
            <div>студента 4 семестра обучения</div>
            <div>Теньшова Михаила Валентиновича</div>
            <div>гр. ОБИ-32305ХБКрвр</div>
            <div>Приложение "Дневник путешествий"</div>
            @auth
                <div>Перейдите в <a class="hover:underline" href="{{ route('travels.travels') }}">путешествия</a>, чтобы начать работу</div>
            @else
                <div><a class="hover:underline" href="{{ route('register') }}">Зарегистрируйтесь</a>, или <a class="hover:underline" href="{{ route('login') }}">войдите</a> в ранее созданный аккаунт</div>
            @endauth

        </main>
    </div>

</x-app-layout>

