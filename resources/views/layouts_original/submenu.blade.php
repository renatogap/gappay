<li class="dropdown">
    <a
            href="#"
            style="color: white"
            class="dropdown-toggle item-menu" data-toggle="dropdown" role="button" aria-haspopup="true"
            aria-expanded="false"
            onmouseover="this.style.backgroundColor='#5a5a5a'" onmouseout="this.style.backgroundColor='transparent'"
    >
        {{$submenu->nome}}
        <span
            class="caret"
        ></span>
    </a>
    <ul class="dropdown-menu">
        @foreach($submenu->submenus as $item)
            @if(isset($item->submenus))
                {!! View::make('layouts.submenu', ['submenu' => $item]) !!}
            @else
                <li
                    onmouseover="this.style.backgroundColor='#5a5a5a'"
                    onmouseout="this.style.backgroundColor='transparent'"
                >
                    <a href="{{url($item->acao)}}">{{$item->nome}}</a>
                </li>
            @endif
        @endforeach
    </ul>
</li>