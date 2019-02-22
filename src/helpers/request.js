export function request(url, method, successHandler, errorHandler) {
  const http = new XMLHttpRequest();

  http.open(method, url, true);

  http.onreadystatechange = () => {
    if (http.status == 200 && http.readyState == 4) {
      if (successHandler) successHandler(http);
    } else if (http.status !== 200) {
      if (errorHandler) errorHandler(http);
    }
  };

  http.send();
}
