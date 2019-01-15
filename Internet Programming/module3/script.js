//Resets values
function reset(){
    document.getElementById("numArray").value = "";
    document.getElementById("min").value = "";
    document.getElementById("max").value = "";
    document.getElementById("sum").value = "";
    document.getElementById("mean").value = "";
    document.getElementById("median").value = "";
    document.getElementById("mide").value = "";
    document.getElementById("stdDev").value = "";
    document.getElementById("variance").value = "";
}
//Calculates the median of entered values
function calcMedian (numArray) {
    var sortedNums = numArray.sort(function(a, b){return a-b});
    var length = sortedNums.length;
    var median = 0;
    if(length == 1) median = sortedNums[0];
    else if(length == 2) median = (sortedNums[0] + sortedNums[1] / 2.0)
    else {
        if(sortedNums.length % 2 == 0){
        median = (sortedNums[length / 2] + sortedNums[(length / 2) - 1]) / 2.0;
        }
        else{
           median = sortedNums[parseInt(length / 2)]; 
        } 
        
        }
    return median;
}

//Calculates the most common value of the entered values
function calcMode(numArray) {
    var modes = [], count = [], i, number, maxIndex = 0;
 
    for (i = 0; i < numArray.length; i += 1) {
        number = numArray[i];
        count[number] = (count[number] || 0) + 1;
        if (count[number] > maxIndex) {
            maxIndex = count[number];
        }
    }
 
    for (i in count)
        if (count.hasOwnProperty(i)) {
            if (count[i] === maxIndex) {
                modes.push(Number(i));
            }
        }
 
    return modes;
}
//Calculates the standard deviation 
function calcStdDev(numArray) {
    return Math.sqrt(calcVariance(numArray)).toFixed(2);
}

//Calculates the sum of all the values
function calcSum(numArray) {
    var sum = 0;
    for(i = 0; i < numArray.length; i++){
        sum += parseInt(numArray[i].valueOf());
    }
    return (sum).toFixed(2);
}

//Calculates the mean of entered values
function calcMean(numArray) {
        return calcSum(numArray) / numArray.length;
     }

//Calculate the variance
function calcVariance(numArray) {
   var mean = calcMean(numArray);
   var variance = 0;
   var sum = 0;
   var length = numArray.length;
   numArray.map((n)=>sum += Math.pow((mean - n),2));
   variance = sum / length;
   return variance.toFixed(2);
}

//Finds the max of the value
function findMax(numArray) {
    var sortedArray = numArray.sort();
    var max = Math.max.apply(null, sortedArray);
    
    return max.toFixed(2);
}

//Finds the min of the values
function findMin(numArray) {
    var sortedArray = numArray.sort();
    var min = Math.min.apply(null, sortedArray);
    return min.toFixed(2);
}

//Creates the array and performs the other functions
function performStatistics() {
    var userInput = document.getElementById("numArray").value;
    var stringArray = userInput.split(" ");
    var numArray = stringArray.map((s)=>parseInt(s))
    if(isNaN(userInput[0])){
        alert("Please enter valid numbers.");
        return false;
    }else{
        document.getElementById("min").value = findMin(numArray);
        document.getElementById("max").value = findMax(numArray);
        document.getElementById("sum").value = calcSum(numArray);
        document.getElementById("mean").value = calcMean(numArray);
        document.getElementById("median").value = calcMedian(numArray);
        document.getElementById("mode").value = calcMode(numArray);
        document.getElementById("stdDev").value = calcStdDev(numArray);
        document.getElementById("variance").value = calcVariance(numArray);
        return true;
    }
}