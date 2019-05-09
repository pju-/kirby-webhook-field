export function request(url, method, successHandler, errorHandler, payload) {
  const http = new XMLHttpRequest();

  http.open(method, url, true);

  http.onreadystatechange = () => {
    if (http.readyState == 4 && http.status == 200) {
      if (successHandler) successHandler(http);
    } else if (http.readyState == 4 && http.status !== 200) {
      if (errorHandler) errorHandler(http);
    }
  };

  if (payload) {
    http.setRequestHeader("Content-Type", "application/json");
    http.send(JSON.stringify(payload));
  } else {
    http.send();
  }
}
