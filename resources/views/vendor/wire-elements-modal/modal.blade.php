<div>
    @isset($jsPath)
        <script>{!! file_get_contents($jsPath) !!}</script>
    @endisset

    <div
        x-data="LivewireUIModal()"
        x-on:close.stop="setShowPropertyTo(false)"
        x-on:keydown.escape.window="show && closeModalOnEscape()"
        x-show="show"
        style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; z-index:1055; overflow-y:auto;"
    >
        {{-- Backdrop --}}
        <div
            x-show="show"
            x-on:click="closeModalOnClickAway()"
            style="position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5);"
        ></div>

        {{-- Contenu centré --}}
        <div style="display:flex; align-items:flex-start; justify-content:center; min-height:100%; padding:2rem 1rem;">
            <div
                x-show="show && showActiveComponent"
                id="modal-container"
                x-trap.noscroll.inert="show && showActiveComponent"
                aria-modal="true"
                style="position:relative; width:100%; max-width:750px; background:#fff; border-radius:0.375rem; box-shadow:0 10px 40px rgba(0,0,0,0.3); overflow:hidden;"
            >
                @forelse($components as $id => $component)
                    <div x-show.immediate="activeComponent == '{{ $id }}'" x-ref="{{ $id }}" wire:key="{{ $id }}">
                        @livewire($component['name'], $component['arguments'], key($id))
                    </div>
                @empty
                @endforelse
            </div>
        </div>
    </div>
</div>
