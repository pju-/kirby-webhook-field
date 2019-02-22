export function request(url, method, successHandler, errorHandler) {
  const http = new XMLHttpRequest();

  http.open(method, url, true);

  http.onreadystatechange = () => {
    if (http.status == 200 && http.readyState == 4) {
      successHandler(http);
    } else if (http.status !== 200) {
      this.status = 'error';
      errorHandler(http);
    }
  };

  http.send();
}
