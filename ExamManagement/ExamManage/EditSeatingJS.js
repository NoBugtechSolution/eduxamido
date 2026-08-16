let selectedSt=[];
selectedSt["td"]=0
selectedSt["pickedRollNo"]=0
const Inventory=document.getElementById("Inventory")
const SaveChanges=document.getElementById("SaveChanges")
const SideBar=document.querySelector(".selected-list")
const pickedRoll = document.getElementById("pickedRoll");
const Deselect = document.getElementById("Deselect");
let reloadallow=false;


function addRow(ClassID, object) {
  table = document.getElementById("C-" + ClassID);
  let row = table.rows;
  let columnCount = table.rows[0].cells.length;

  let rowscount = row.length;

  let Therow = table.insertRow(rowscount - 1);
  cell = Therow.insertCell();
  cell.addEventListener("click",function(event){
    selectAllRow(ClassID,(rowscount - 1))
  })
  cell.textContent = "R" + (rowscount - 1);
  for (let i = 1; i < columnCount - 1; i++) {
    let cell = Therow.insertCell();
    cell.setAttribute("data-key",`${ClassID}-${(rowscount - 1)}-${i}`)
    cell.innerHTML = `<div onclick='checkthevalue(this.parentNode)' class='nill'>___</div>`;
    assignEvent(cell);
  }
  ColumnTr = table.querySelector("#ColumnObject");
  object.textContent = "+" + (ColumnTr.rowSpan + 1);
  ColumnTr.rowSpan = ColumnTr.rowSpan + 1;
}

function addColumn(ClassID, object) {
  table = document.getElementById("C-" + ClassID);
  let row = table.rows;
  let columnCount = table.rows[0].cells.length;
  let column = table.rows[0];
  newCell = column.insertCell(columnCount - 1);
  newCell.addEventListener("click",function(event){
    selectAllCoumn(ClassID,(columnCount - 1))
  })
  newCell.textContent = "C" + (columnCount - 1);
  for (i = 1; i < table.rows.length - 1; i++) {
    let column = table.rows[i];
    newCell = column.insertCell(columnCount - 1);
    newCell.setAttribute("data-key",`${ClassID}-${i}-${(columnCount - 1)}`)
    newCell.innerHTML = `<div onclick='checkthevalue(this.parentNode)' class='nill'>___</div>`;
    assignEvent(newCell);
  }
  RowTr = table.querySelector("#RowObject");
  object.textContent = "+" + RowTr.colSpan;
  RowTr.colSpan = RowTr.colSpan + 1;
}

function checkthevalue(Object) {
  let Choose="td";
  if(Object.classList.contains("pickedRollNo")){
    Choose="pickedRollNo";
  }
  let CheckBox = Object.querySelector("input");
  if(CheckBox==null){
    addToNill(Object)
    return
  }
  if (CheckBox.checked) {
    selectedSt[Choose]--;
    CheckBox.checked = false;
  } else {
    selectedSt[Choose]++;
    CheckBox.checked = true;
  }
  AddToInventoryActive()
}


function selectAllCoumn(classID,column){
  let table=document.getElementById("C-"+classID);
  let row = table.rows;
  let check=null;
  for(let i=1;i<row.length-1;i++){
   let Thetr=row[i].cells[column];
   let inputCheck=Thetr.querySelector("input")
   if(inputCheck){
    if(check==null){
      check=(!inputCheck.checked)
    }
    if(check!=inputCheck.checked){
      if(check){
        selectedSt["td"]++;
      }else{
        selectedSt["td"]--;
      }
    }
    inputCheck.checked=check
   }
  }
  AddToInventoryActive()
  
}
function selectAllRow(classID,RowNum){
  let table=document.getElementById("C-"+classID);
  let column = table.rows[RowNum].cells;
  let check=null;
  for(let i=1;i<=column.length-1;i++){
    let Thetr=column[i];
    let inputCheck=Thetr.querySelector("input")
    if(inputCheck){
      if(check==null){
        check=(!inputCheck.checked)
      }
      if(check!=inputCheck.checked){
        if(check){
          selectedSt["td"]++;
        }else{
          selectedSt["td"]--;
        }
      }
      inputCheck.checked=check
    } 
  }

  AddToInventoryActive()
  
}
function AddToInventoryActive(){
  if(selectedSt["td"]==0){
    Inventory.classList.remove("Active")
  }else{
    SideBar.classList.add("Open")
    Inventory.classList.add("Active")
  }
  DeselectCheck()
}

function meanuOpen(){
  if(SideBar.classList.contains("Open")){
    SideBar.classList.remove("Open")
  }else{
    SideBar.classList.add("Open")
  }
}



function getSeatToTransfer(){
  let InventoryData=[];
  let TableData=[];
  document.querySelectorAll('td input[type="checkbox"]').forEach((checkbox) => {
    if (checkbox.checked) {
      TableData.push(checkbox.parentNode)
    }
  });
  pickedRoll.querySelectorAll('input[type="checkbox"]').forEach((checkbox) => {
    if (checkbox.checked) {
      InventoryData.push(checkbox.parentNode)
    }
  });
  return (TableData.length!=0)?TableData:InventoryData;
}


function addToNill(Object){
  next=null;
  let SelectedObject=getSeatToTransfer()
  
  SelectedObject.sort((a, b) => {
    let textA = a.querySelector('input')?.value.trim() || "";
    let textB = b.querySelector('input')?.value.trim() || "";
    
    return textA.localeCompare(textB); 
  });
  SelectedObject.forEach(element => {
    element.classList.add("selected")
  });

  if(SelectedObject.length>0){
      Object.classList.add("selected")
      let MainOut=AutoAssign(SelectedObject.length,[Object],[Object])
      MainOut.sort((a, b) => {
        let textA = a.getAttribute("data-key") || "";
        let textB = b.getAttribute("data-key") || "";
        
        return textA.localeCompare(textB); 
      });
      MainOut.forEach((element,index) => {
        swappingToTable(SelectedObject[index],element)
      });
      MainOut.forEach(element => {
        element.classList.remove("selected")
      });
      
  }
  
  SelectedObject.forEach(element => {
    element.classList.remove("selected")
  });
  DeselectCheck()
}


function AutoAssign(SelectedLength,Object,save) {
  if(Object.length==0){
    return save;
  }
  if(SelectedLength<=save.length){
    return save
  }
  let newsave=[];
  for(let i=0;i<Object.length;i++){
    if(SelectedLength<=save.length){
      return save
    }
    let nextCheck=checkNextNill(Object[i]);
    for(let j=0;j<nextCheck.length;j++){
      nextCheck[j].classList.add("selected")
      save.push(nextCheck[j]);
      newsave.push(nextCheck[j])
      if(SelectedLength<=save.length){
        return save
      }
    }
  }
  if(SelectedLength<=save.length){
    return save
  }
  return AutoAssign(SelectedLength,newsave,save)
}



function checkNextNill(tdElement){
  let row = tdElement.parentNode;
    let table = row.parentNode;

    let rows = Array.from(table.rows);
    let cells = Array.from(row.cells);

    let colIndex = cells.indexOf(tdElement);
    let rowIndex = rows.indexOf(row);

    let adjacentCells = {
        bottom: rowIndex < rows.length - 1 ? rows[rowIndex + 1].cells[colIndex] : null,
        next: colIndex < row.cells.length - 1 ? row.cells[colIndex + 1] : null,
        previous: colIndex > 0 ? row.cells[colIndex - 1] : null,
        top: rowIndex > 0 ? rows[rowIndex - 1].cells[colIndex] : null,
    };
    let Avaiables=[]
    for (let key in adjacentCells) {
        let cell = adjacentCells[key];
        if (cell && (cell.querySelector("div.nill")) && (!cell.classList.contains("selected"))) {
          Avaiables.push(cell);
        }
    }
    return Avaiables;
}



function pickSelected() {
  TheDiv = document.querySelector("#theMainTable");
  ListD = document.getElementById("pickedRoll");
  TheDiv.querySelectorAll('input[type="checkbox"]').forEach((checkbox) => {
    if (checkbox.checked) {
      addDivToBox(checkbox.parentNode,ListD)
    }
  });
  selectedSt["td"]=0
  AddToInventoryActive()
  saveChangesCheck()
}






let draggedElement = null;
let isDraggingFromPickedRoll = false;
function DraggingSet() {
  document.querySelectorAll("td").forEach((td) => {
    if (td.id === "RowObject" || td.id === "ColumnObject") {
      td.draggable = false;
    } else {
      assignEvent(td);
    }
  });

  if (pickedRoll) {
    pickedRoll.addEventListener("dragover", function (event) {
      event.preventDefault();
      this.classList.add("drag-over");
    });

    pickedRoll.addEventListener("dragleave", function () {
      this.classList.remove("drag-over");
    });

    pickedRoll.addEventListener("drop", function (event) {
      event.preventDefault();
      this.classList.remove("drag-over");

      if (draggedElement && !isDraggingFromPickedRoll) {
        if (draggedElement.querySelector("div").classList.contains("nill")) {
          return;
        }
        addDivToBox(draggedElement,pickedRoll)
        
      }
    });
  }
}

function addDivToBox(draggedElement1,pickedRoll) {
  let newDiv = document.createElement("div");
  newDiv.classList.add("pickedRollNo");
  newDiv.draggable = true;
  newDiv.innerHTML = draggedElement1.innerHTML;

  newDiv.addEventListener("dragstart", function (event) {
    draggedElement = this;
    isDraggingFromPickedRoll = true; 
    event.dataTransfer.effectAllowed = "move";
    event.dataTransfer.setData("text/plain", this.innerHTML);
    this.classList.add("dragging");
  });

  newDiv.addEventListener("dragend", function () {
    this.classList.remove("dragging");
  });
  pickedRoll.appendChild(newDiv);
  draggedElement1.innerHTML = "<div onclick='checkthevalue(this.parentNode)' class='nill'>___</div>";
  saveChangesCheck()
  
}



function assignEvent(td) {
  td.draggable = true;

  td.addEventListener("dragstart", function (event) {
    draggedElement = this;
    isDraggingFromPickedRoll = false; // Dragging from table
    event.dataTransfer.effectAllowed = "move";
    event.dataTransfer.setData("text/plain", this.innerHTML);
    this.classList.add("dragging");
  });

  td.addEventListener("dragover", function (event) {
    event.preventDefault();
    this.classList.add("drag-over");
  });

  td.addEventListener("dragleave", function () {
    this.classList.remove("drag-over");
  });

  td.addEventListener("drop", function (event) {
    event.preventDefault();
    this.classList.remove("drag-over");
    
    if (isDraggingFromPickedRoll && draggedElement) {
      swappingToTable(draggedElement, this);
    } else if (draggedElement !== this) {
      // Swap the innerHTML between two <td>
      let temp = this.innerHTML;
      this.innerHTML = draggedElement.innerHTML;
      draggedElement.innerHTML = temp;
    }
    ChangedTables(this)
    ChangedTables(draggedElement)
  });

  td.addEventListener("dragend", function () {
    this.classList.remove("dragging");
  });
}



function swappingToTable(draggedElement, target) {
  let draggedElementtemp = draggedElement.innerHTML;
  if (target.querySelector("div").classList.contains("nill")) {
    if(draggedElement.tagName.toLowerCase() === "td"){
      draggedElement.innerHTML="<div onclick='checkthevalue(this.parentNode)' class='nill'>___</div>"
    }else{
      draggedElement.remove();
    }
  } else {
    draggedElement.innerHTML = target.innerHTML;
  }
  target.innerHTML = draggedElementtemp;
  ChangedTables(target)
  saveChangesCheck()
}
let studentsChanged=[];




function ChangedTables(data){
  let input=data.querySelector("#CheckValues")
  if(!input){
    return
  }
  let studID=input.getAttribute("data-key");
  let MainSeat=data.getAttribute("data-key");
  let StudOldSeat=input.value;
  if(MainSeat==null || StudOldSeat==null){
    return
  }
  if(MainSeat==StudOldSeat){
    let index = studentsChanged.indexOf(studID);
    if (index !== -1) {
        studentsChanged.splice(index, 1);
    }

  }else{
    if(studentsChanged.indexOf(studID)==-1){
      studentsChanged.push(studID)
    }

  }
  
  // if(studentsChanged.indexOf(value)==-1){
  //   studentsChanged.push(value)
  // }
  saveChangesCheck()
  
}

DraggingSet();



function saveChangesCheck(){
 if(studentsChanged.length!=0 && pickedRoll.childNodes.length==0){
  SaveChanges.classList.add("Active")
 }else{
  SaveChanges.classList.remove("Active")
 }
}

function selectAllBu(){
  let check=null;
  pickedRoll.querySelectorAll('input[type="checkbox"]').forEach((checkbox) => {
    if(check==null){
      check=!checkbox.checked
    }
    checkbox.checked=check
  });
  DeselectCheck()
}



function finalSendData(){

  let values=[];
  studentsChanged.forEach(element => {
    object=document.querySelector('[data-key="'+element+'"');
    let data=object.parentNode.getAttribute("data-key")
    values.push(element+"-"+data)
  });
  
  if(values.length==0){
    alert("No Changes");
    return;
  }
  let con=confirm("Do you want to save "+values.length+" changes?")
  if(con){
    reloadallow=true
  Myform.changes.value=values;
  Myform.submit();
  }
}

function resetAnimationDelay(){
  document.querySelectorAll(".seat").forEach((seat)=>{
    seat.style.animationDelay=".02s"
  });
  
}

function DeselectCheck(){
  let flag=0;
  Deselect.classList.remove("Active")
  document.querySelectorAll('input[type="checkbox"]').forEach((element)=>{
    if(element.checked){
      flag=1
      Deselect.classList.add("Active")
      return;
    }
    
  })
}

function Deselectfun(){
  document.querySelectorAll('input[type="checkbox"]').forEach((element)=>{
    element.checked=false;
  })
  selectedSt["td"]=0
  selectedSt["pickedRollNo"]=0
  AddToInventoryActive()
}


window.addEventListener("beforeunload", function (event) {
  if(!reloadallow){
    if(studentsChanged.length!=0 || pickedRoll.childNodes.length!=0){
    event.preventDefault(); 
    
    event.returnValue = "Are you sure you want to leave?";
    }
  }
});

setTimeout(resetAnimationDelay,5000);
