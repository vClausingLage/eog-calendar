# for the editor


```javascript
import wixData from "wix-data"

// document ready
$w.onReady(function () {
    // listen to message events
  $w("#html2").onMessage((event) => {
    let receivedData = event.data;
    // store date and appointment data
    const date = receivedData.dateTimeCheck
    const newAppointment = receivedData.newAppointment

    // check and insert new appointment 
    if (newAppointment) {
      wixData 
        .insert('Appointments', newAppointment)
        .then(res => console.log(res))
        .catch(err => console.log(err))
    }

    // send date check result function
    function sendDateCheckResult(bool) {
      if (bool === true) {
        $w('#html2').postMessage({dateCheck: 'pass'})
      } else {
        $w('#html2').postMessage({dateCheck: 'nopass'})
      }
    }

    // query appointments for submitted date and return result
    if (date) {
      wixData
        .query("Appointments")
        .find()
        .then(res => {
          let found = false
          res.items.forEach(el => {
            if (new Date(el?.date)?.getTime() === new Date(date).getTime()) {
              found = true
            } else {}
          })
          // send result back to html component
          if (found) sendDateCheckResult(false)
          if (!found) sendDateCheckResult(true)
        })
        .catch(err => console.log(err))
    }
  });
});

```