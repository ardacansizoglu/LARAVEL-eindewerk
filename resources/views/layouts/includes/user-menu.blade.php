<div class="bg-red-100 py-2 px-4">
    <div class="container mx-autp px-4 flex justify-center gap-8">
        <a href="{{ route('profile.edit') }}" style="text-decoration: none; color: black;"
            onmouseover="this.style.textDecoration='underline'; this.style.textDecorationColor='orange';"
            onmouseout="this.style.textDecoration='none';">
            <i class="fa-solid fa-user-pen"></i> Wijzig profiel
        </a>

        <a href="{{ route('favorites') }}" style="text-decoration: none; color: black;"
            onmouseover="this.style.textDecoration='underline'; this.style.textDecorationColor='orange';"
            onmouseout="this.style.textDecoration='none';">
            <i class="fa-solid fa-heart"></i> Favorieten
        </a>

        <a href="{{ route('orders.index') }}" style="text-decoration: none; color: black;"
            onmouseover="this.style.textDecoration='underline'; this.style.textDecorationColor='orange';"
            onmouseout="this.style.textDecoration='none';">
            <i class="fa-solid fa-bag-shopping"></i> Mijn bestellingen
        </a>

        <a href="{{ route('logout') }}" style="text-decoration: none; color: black;"
            onmouseover="this.style.textDecoration='underline'; this.style.textDecorationColor='orange';"
            onmouseout="this.style.textDecoration='none';">
            <i class="fa-solid fa-right-from-bracket"></i> Logout
        </a>


    </div>
</div>
