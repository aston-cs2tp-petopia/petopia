function checkForm() {
    let name = document.getElementById("name");
    let email = document.getElementById("email")
    let text = document.getElementById("text")
    let submit = document.getElementById("submit")
  
    if (name.value === null) {
      alert("please enter a name")
      return false;
    }
  
    if (email.value === null) {
      alert("please enter an email")
      return false;
    }

    if (text.value === null) {
        alert("please give us your message")
        return false;
      }
  
    alert(name.value + " " + email.value + " " + text.value + " ")
    return false;
  }