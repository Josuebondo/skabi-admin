 <aside class="hidden md:flex w-20 bg-slate-900 border-r border-dark-border flex-col items-center py-6 shrink-0">
     <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center mb-10 shadow-lg shadow-primary/20">
         <span class="material-icons text-white">inventory</span>
     </div>
     <nav class="flex-1 flex flex-col gap-4">
         <a desabled class="w-12 h-12 flex items-center justify-center rounded-xl bg-primary/10 text-primary  transition-all group relative" href="/documents" title="Tableau de bord non disponible">
             <span class="material-icons">description</span>
             <span class="absolute left-14 bg-slate-700 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 whitespace-nowrap pointer-events-none z-50">Documents</span>
         </a>
         <a desabled class="w-12 h-12 flex items-center cursor-not-allowed justify-center rounded-xl text-slate-500 hover:bg-slate-800 hover:text-primary transition-all group relative" href="/inventaire" title="Inventaire non disponible">
             <span class="material-icons">inventory</span>
             <span class="absolute left-14 bg-slate-700 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 whitespace-nowrap pointer-events-none z-50">Inventaire</span>
         </a>
         <a class="w-12 h-12 flex items-center justify-center rounded-xl   text-slate-500 hover:bg-slate-800 hover:text-primary transition-all group relative" href="/mouvements" title="Mouvements">
             <span class="material-icons">swap_horiz</span>
             <span class="absolute left-14 bg-slate-700 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 whitespace-nowrap pointer-events-none z-50">Mouvements</span>
         </a>
         <a desabled class="w-12 h-12 flex items-center cursor-not-allowed justify-center rounded-xl text-slate-500 hover:bg-slate-800 hover:text-primary transition-all group relative" href="#" title="Rapports non disponible">
             <span class="material-icons">analytics</span>
             <span class="absolute left-14 bg-slate-700 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 whitespace-nowrap pointer-events-none z-50">Rapports</span>
         </a>
     </nav>
     <div>

         <button id="profileBtn"
             class="mt-auto w-12 h-12 flex items-center justify-center rounded-xl hover:bg-slate-800 transition-all relative"
             title="Profil">

             <div id="avatarContainer"
                 class="w-10 h-10 rounded-full border-2 border-slate-700 overflow-hidden flex items-center justify-center bg-primary text-white font-bold text-sm">

                 <!-- Image si existe -->
                 <img id="avatarImg"
                     class="w-full h-full object-cover hidden"
                     alt="Profil" />

                 <!-- Initiales si pas d'image -->
                 <span id="avatarInitials"></span>

             </div>
         </button>

         <!-- logout btn -->
         <button id="logoutBtn" class="mt-4 w-12 h-12 flex items-center justify-center rounded-xl text-slate-500 hover:bg-slate-800 hover:text-primary transition-all group relative" title="Déconnexion">
             <span class="material-icons">logout</span>
             <span class="absolute left-14 bg-slate-700 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 whitespace-nowrap pointer-events-none z-50">Déconnexion</span>
     </div>

 </aside>