document.addEventListener("DOMContentLoaded",()=>{


    document.body.classList.add("loaded");


    darkMode();

    sidebar();

    notifications();

    numbers();


});





// DARK MODE

function darkMode(){


const btn=document.querySelector(".dark-toggle");


if(!btn) return;



if(localStorage.getItem("dark")=="yes"){

    document.body.classList.add("dark");

}



btn.onclick=()=>{


document.body.classList.toggle("dark");


if(document.body.classList.contains("dark")){

localStorage.setItem("dark","yes");


}else{


localStorage.setItem("dark","no");


}


};



}







// MOBILE SIDEBAR

function sidebar(){


let btn=document.querySelector(".menu-toggle");

let side=document.querySelector(".sidebar");



if(!btn || !side) return;



btn.onclick=()=>{


side.classList.toggle("show");


};


}









function numbers(){

    document.querySelectorAll(".stat-card h2").forEach(num=>{

        let text = num.innerText.trim();

        // Keep percentage symbol if it exists
        let hasPercent = text.includes("%");

        let value = parseInt(text.replace("%",""));


        if(isNaN(value)) return;


        let current = 0;


        let step = Math.ceil(value / 50);


        let timer = setInterval(()=>{


            current += step;


            if(current >= value){

                current = value;

                clearInterval(timer);

            }


            num.innerText = hasPercent 
                ? current + "%"
                : current;


        },20);


    });

}