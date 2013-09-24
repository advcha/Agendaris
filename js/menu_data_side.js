_menuCloseDelay=0;           // The time delay for menus to remain visible on mouse out
_menuOpenDelay=0;            // The time delay before menus open on mouse over
_subOffsetTop=0;             // Sub menu top offset
_subOffsetLeft=0;            // Sub menu left offset

/// Style Definitions ///

with(mainStyleVert=new mm_style()){
onbgcolor="#9EE3A9";
oncolor="#1D3B23";
offbgcolor="#61A76D";
offcolor="#F7F9F7";
bordercolor="#367E45";
borderstyle="solid";
borderwidth=1;
separatorcolor="#325235";
separatorsize=2;
padding=4;
fontsize="90%";
fontstyle="bold";
fontfamily="Verdana, Tahoma, Arial";
subimage="./images/submenu_arrow_right-off.gif";
onsubimage="./images/submenu_arrow_right-on.gif";
}

// Main

with(milonic=new menuname("mainMenuVert")){
style=mainStyleVert;
top=0;
left=0;
itemwidth=180;
alwaysvisible=1;
aI("text=HOME;url=front.php;target=front;");
aI("text=DATA PERKARA;showmenu=sub1;target=front;onfunction=openSubmenu();offfunction=closeSubmenu();");
aI("text=DATA KEPEGAWAIAN;showmenu=sub2;target=front;onfunction=openSubmenu();offfunction=closeSubmenu();");
aI("text=DATA KEUANGAN;showmenu=sub3;target=front;onfunction=openSubmenu();offfunction=closeSubmenu();");
aI("text=DATA INVENTARIS;showmenu=sub4;target=front;onfunction=openSubmenu();offfunction=closeSubmenu();");
aI("text=DATA PERPUSTAKAAN;showmenu=sub5;target=front;onfunction=openSubmenu();offfunction=closeSubmenu();");
aI("text=JADWAL SIDANG;showmenu=sub6;target=front;onfunction=openSubmenu();offfunction=closeSubmenu();");
aI("text=ADMIN;showmenu=sub7;target=front;onfunction=openSubmenu();offfunction=closeSubmenu();");
aI("text=LOG OUT;url=index.php?logout;target=_parent;");
}

drawMenus();

