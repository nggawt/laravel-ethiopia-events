

var dateCalendar = function(elem,lenguege){
    "use strict";
    var date = new Date(),
        url = window.location.href,
        createElem = function(el){ return document.createElement(el);},
        getDaysOfMonth = function(getFor,theYear,theMonth,theDay){

            var massge = "please provide whats you loking for",
                theYear = theYear? theYear : date.getFullYear(),
                theMonth = theMonth? theMonth : date.getMonth(),
                theDay = theDay? theDay : date.getDay();

            if(getFor === "getDay" || getFor === "lastDay" ){
                if(getFor === "getDay") return  new Date(theYear, theMonth, theDay).getDay();

                if(getFor === "lastDay") return  new Date(theYear, theMonth, 0).getDate();
            }else{
                return massge;
            }
        },
        getUrl = function(){
            return url.split('//nggawt/')[1]  === 'admin' || url.split('//nggawt:8080/')[1]  === "admin"?true : false;
        },
        months = [],
        weeks = [],
        monthCounter = date.getMonth(),
        monthStr = ["ינואר","פברואר","מרץ","אפריל","מאי","יוני","יולי","אוגוסט","ספטמבר","אוקטובר","נובמבר","דצמבר"],
        weekStr = ["ראשון","שני","שלישי","רביעי","חמישי","שישי","שבת"],
        dayOfWeek = date.getDay(),
        month = date.getMonth(),
        dayOfMont = date.getDate(),
        daysInMonth = date.getDate(),
        year = date.getFullYear(),
        dayOfYear = "",
        // firstDay = new Date(year, month, 1),

        input = elem,
        parent = input.parentElement,
        div = createElem("DIV"),
        divChild = createElem("DIV"),
        divMonth = createElem("DIV"),
        table = createElem("TABLE"),
        tableHead = createElem("THEAD"),
        tableTr = createElem("TR"),

        //tableBody = createElem("TBODY"),
        spanPrev = createElem("span"),
        spanNext = createElem("span"),
        closeBtn = createElem("span"),
        spanCurrentMonth = createElem("span"),

        init = function(){
            
            var classByUrl = getUrl();
            if(! classByUrl){

                div.className = "datepickr-wrapper open forPage";
                divChild.className = "datepickr-calendar forPageDiv";
            }else{
                div.className = "datepickr-wrapper open";
                divChild.className = "datepickr-calendar";
            }
            
            divMonth.className = "datepickr-months";
            spanPrev.className = "datepickr-prev-month";
            spanPrev.innerHTML = "הבא <bold>&lArr;</bold>";
            spanNext.className = "datepickr-next-month";
            spanNext.innerHTML = "<bold>&rArr;</bold> לפני";
            spanCurrentMonth.className = "btn btn-primary btn-xs datepickr-current-month";
            closeBtn.innerHTML = "X";
            closeBtn.className = "btn btn-primary btn-xs pull-left";
            
            tableHead.appendChild(tableTr);
            table.appendChild(tableHead);
            //table.appendChild(tableBody);
            divMonth.appendChild(spanPrev);
            divMonth.appendChild(spanNext);
            divMonth.appendChild(spanCurrentMonth);
            divChild.appendChild(divMonth);
            divChild.appendChild(table);
            divChild.appendChild(closeBtn);
             
            div.appendChild(input);
            div.appendChild(divChild);
            parent.appendChild(div);
            div["style"].direction = "rtl";
            divChild["style"].display = "none";
            //div["style"].right = 600 + "px";
            createCalendar.looper(0,weekStr,createCalendar.appendThead,tableTr);
        };

    var createCalendar = {
        firstDay: new Date(year,month,1),
        isTrue : true,
        fired : false,
        getFullYearDates: function(index,len){
            
            var trb = createElem("TR"),
                tlb = createElem("TBODY");

            for(;index <=  len;index++){
                
                var td = createElem("td"),
                span = createElem("span");
                span.className = "datepickr-day";
                span.innerHTML = index;
                this.appendCh(td,span);

                if(index > (weekStr.length -  this.firstDay.getDay()) && this.isTrue){
                    
                    trb = createElem("tr");
                    trb.className = "first argue";
                    this.isTrue = false;
                }

                if(index ===1){
                    if(this.firstDay.getDay() !== 0){
                        var tdd = createElem("td");
                        tdd.setAttribute("colspan",  this.firstDay.getDay());
                        tdd.innerHTML = "&nbsp;";
                        tdd.className = (this.firstDay.getMonth()) + " : " +this.firstDay.getDay();
                        this.appendCh(trb,tdd);
                    }
                    this.isTrue = true;
                }
                
                if((index % weekStr.length -1) == (weekStr.length - this.firstDay.getDay()) && index > (weekStr.length - this.firstDay.getDay())){
                    trb = createElem("tr");
                    trb.className = "second argue" + index;
                }

                if((index % 7) == 1 && this.firstDay.getDay() == 0){
                    trb = createElem("tr");
                    trb.className = "thired argue";
                }

                if((index % weekStr.length -1) == (- this.firstDay.getDay()) && index > (weekStr.length - this.firstDay.getDay())){
                    trb = createElem("tr");
                    trb.className = "fifth argue" + index;
                }
                this.appendCh(trb,td);
                this.appendCh(tlb,trb);
            }//END FOR LOOP SECOND
            return tlb;
        },
       /* */
        datesInYear: function(theYear,len,currentDate){

            var len = len? len : monthStr.length,
                issetDate = currentDate;
            if(theYear && typeof theYear === "number" && theYear.toString().length === 4) this.firstDay.setYear(theYear);
            //loop
            for(var ii = 0;ii < len;ii++){
                this.firstDay.setMonth(ii);
                this.firstDay.setDate(1);
                months[ii] = this.getFullYearDates(1,getDaysOfMonth("lastDay",this.firstDay.getFullYear(),ii + 1));
                // break;
            }
            
            if(! currentDate || currentDate > monthStr.length || currentDate < 0 || typeof currentDate !== "number"){
                //console.log(iscurrentDate);
                if(currentDate !== 0) currentDate = (months.length ===  monthStr.length)?date.getMonth():(months.length === len)?len - 1:0;
            }
            this.appendCh(table,months[currentDate]);
            spanCurrentMonth.innerHTML = monthStr[currentDate] + " " + this.firstDay.getFullYear();
            this.updateDate(null,currentDate,null);
            monthCounter = currentDate;

            if(issetDate) this.setSpanPlaceHolder();
        },
        checkYear: function(theYear){
            return this.firstDay.getFullYear() === theYear;
        },
        appendThead : function(arg,arg2){
            var th = createElem("th");
            th.innerHTML = arg2;
            this.appendCh(arg,th);
        },
        appendCh : function(parent,child){
            parent.appendChild(child);
        },
        nextYear: function(inc){
            this.firstDay.setYear(this.firstDay.getFullYear() + inc);
        },
        prevYear: function(){
            this.firstDay.setYear(this.firstDay.getFullYear() - inc);
        },
        fire: function(theYear,len,theMonth){

            if(! this.fired){
                this.datesInYear(theYear,len,theMonth);
                divChild["style"].display = "inline-block";
                this.fired = true;
            }
        },
        prevMonth: function(){

            monthCounter--;
            if(monthCounter  < 0){
                monthCounter = 11;
                
                table.removeChild(table.children[1]);
                this.datesInYear(this.firstDay.getFullYear() -1,"",monthCounter);
                spanCurrentMonth.innerHTML = monthStr[monthCounter] + " " + this.firstDay.getFullYear();
                this.updateDate(null,monthCounter,null);
                this.setSpanPlaceHolder();
                return;
            }
            this.updateDate(null,monthCounter,null);
            table.removeChild(table.children[1]);
            table.appendChild(months[monthCounter]);
            spanCurrentMonth.innerHTML = monthStr[monthCounter] + " " + this.firstDay.getFullYear();
            this.setSpanPlaceHolder();
        },
        nextMonth: function(){
            
            monthCounter++;
            if(monthCounter  > 11){
                monthCounter = 0;

                table.removeChild(table.children[1]);
                this.datesInYear(this.firstDay.getFullYear() + 1,null,monthCounter);
                spanCurrentMonth.innerHTML = monthStr[monthCounter] + " " + this.firstDay.getFullYear();
                this.updateDate(null,monthCounter,null);
                this.setSpanPlaceHolder();
                return;
            } 
            table.removeChild(table.children[1]);
            table.appendChild(months[monthCounter]);
            this.updateDate(null,monthCounter,null);
            spanCurrentMonth.innerHTML = monthStr[monthCounter] + " " + this.firstDay.getFullYear();
            this.setSpanPlaceHolder();
        },
        formatedDate: function(){
            this.firstDay.setMonth(monthCounter);
            var formatMonth = (this.firstDay.getMonth() + 1) < 10?"0" + (this.firstDay.getMonth()+1):this.firstDay.getMonth()+1;

            return (formatMonth) + "-" +  this.firstDay.getFullYear();
        },
        updateDate : function(theYear,theMonth,theDay){
            if(theYear) this.firstDay.setYear(theYear);
            if(theMonth) this.firstDay.setMonth(theMonth);
            if(theDay) this.firstDay.setDate(theDay);
        },
        setSpanPlaceHolder : function(days){
            days = !days?"00-": days < 10?"0"+days + "-":days+ "-";
            var fullDates = days +  this.formatedDate();
            input.value = fullDates;
            return fullDates;
        },
        looper : function(index,lenOb,fn,param1,param2,option){
            var number = (typeof lenOb === "number")? lenOb:null,
                arr = (Array.isArray(lenOb))? lenOb.length -1:null,
                lent = number || arr,tr = createElem("tr"),
                option = option?option:null;

            for (index; index <= lent; index++) {
                if(number){
                    fn.call(this,index,param1,param2);
                }else{
                    fn.call(this,param1,lenOb[index]);
                }
            }
        }
    };
    
    init();
    createCalendar.fire();

    ///////*********listners************/////
    spanNext.addEventListener("click",function(){
        createCalendar.prevMonth();
    });
    spanPrev.addEventListener("click",function(){
        createCalendar.nextMonth();
    });
    input.addEventListener("click",function(e){
        divChild["style"].display = divChild["style"].display === "inline-block"? "none":"inline-block";
    });
    table.addEventListener("click",function(e){
        if(e.target.tagName === "SPAN"){
            var fullDates = createCalendar.setSpanPlaceHolder(e.target.innerHTML);
            divChild["style"].display = "none";
            if(! getUrl()) return;
            if(myChart) myChart.destroy();
            var labels =[],data=[],
                responses = getRequest("/test?pickDate="+fullDates,"",{
                    response: function(res){
                        // console.log(res);
                        for(ii in res.data){
                            data.push(res.data[ii] || 1);
                            labels.push(ii);
                        }
                        //selectedItem.options[res.selected].setAttribute("selected",true);
                        canvas(data,labels,res.status);
                    }
                });
        }else{
            if(divChild["style"].display === "inline-block") divChild["style"].display =  "none";
        }
    });
    spanCurrentMonth.addEventListener("click",function(e){
        if(e.target.tagName === "SPAN"){
            var fullDates =createCalendar.setSpanPlaceHolder();
             divChild["style"].display = "none";

            if(! getUrl()) return;
            if(myChart) myChart.destroy();

             //getRequest("?include="+fullDates);
             var labels =[],data=[],
                 responses = getRequest("/test?pickDate="+fullDates,"",{
                     response: function(res){
                         // console.log(res);
                         for(ii in res.data){
                             data.push(res.data[ii] || 1);
                             labels.push(ii);
                         }
                         //selectedItem.options[res.selected].setAttribute("selected",true);
                         canvas(data,labels,res.status);
                     }
                 });
             //console.log(fullDates);
        }
    });
    closeBtn.addEventListener("click",function(e){
        if(e.target.tagName === "SPAN"){
            //createCalendar.setSpanPlaceHolder(e.target.innerHTML);
            divChild["style"].display = "none";
        }
    });
    
    //spanNext.addEventListener("click",createCalendar.nextMonth);
    //return createCalendar.fired;
};
var run = false;
function runDateCalendar(input){
    if(! run) {
        dateCalendar(input);
        run = true;
    }
}
//var ob = datecalendar();
    
    /*ob.getDaysInYear({
        option : function(){
            console.log(this.getDaysInMonth(2017,9));
        }
    });*/
