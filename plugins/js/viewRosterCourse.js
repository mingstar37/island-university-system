    var dayArr = ["Sunday", "Monday", "Tuesday", 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    function onCheckValidate(e) {
        e.preventDefault();

        let selDate = e.target.value;

        let tempDate = selDate;
        if (selDate != "" || selDate != undefind) {
            tempDate += " 23:59:59";
        }
        let realDate = new Date(tempDate);
        let wNumber = realDate.getDay();

        let selDay = dayArr[wNumber];

        let existDayInputs = document.getElementsByClassName("allow-days");
        let existDays = [];

        let bExist = false;
        for (let i = 0; i < existDayInputs.length; i++) {
            if (selDay === existDayInputs[i].value) {
                bExist = true;
                break;
            }
        }

        if (bExist == false) {
            alert('Please select again!');
            e.target.value = '';
            return false;
        }

        return true;
    }
