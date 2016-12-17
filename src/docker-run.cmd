cd %~pd0

docker run -it --rm  -v %cd%\..\scrutinizer:/gitbook docker-gitbook bash -c "gitbook build"
