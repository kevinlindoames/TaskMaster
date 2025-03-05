# Etapa de construcción
FROM node:20 AS build
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm install
COPY . .
RUN npm run build

# Etapa de producción
FROM nginx:alpine
# Eliminar el contenido por defecto de Nginx
RUN rm -rf /usr/share/nginx/html/*
# Copiar los archivos de la construcción de Angular
COPY --from=build /app/dist/taskmaster-frontend/browser /usr/share/nginx/html
# Renombrar index.csr.html a index.html si es necesario
RUN if [ -f /usr/share/nginx/html/index.csr.html ]; then \
    mv /usr/share/nginx/html/index.csr.html /usr/share/nginx/html/index.html; \
    fi
# Copiar la configuración de Nginx personalizada
COPY nginx.conf /etc/nginx/conf.d/default.conf
EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]