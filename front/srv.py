#!/usr/bin/python3

import requests
from http.server import BaseHTTPRequestHandler, HTTPServer

HOST = ""
PORT = 4000

class OrdersServer(BaseHTTPRequestHandler):
  def getOrders(self):
    orders = requests.get('http://localhost:9500/commandes').json()
    self.send_response(200)
    self.send_header("content-type", "text/html")
    self.end_headers()
    self.wfile.write(bytes('<meta http-equiv="refresh" content="1" />', "utf-8"))
    self.wfile.write(bytes("<html><head><title></title></head><body>", "utf-8"))
    self.wfile.write(bytes("<table witdh='100%'><tr><th>Nom</th><th>Prenom</th><th>Nombre de repas command&eacute</th><th>Nom du repas</th><th>Date livraison demand&eacute</th><th>Montant total</th></tr>", "utf-8"))

    for order in orders:
      self.wfile.write(bytes("<tr>", "utf-8"))
      self.wfile.write(bytes("<td>" + order["nom"] + "</td>", "utf-8"))
      self.wfile.write(bytes("<td>" + order["prenom"] + "</td>", "utf-8"))
      self.wfile.write(bytes("<td>" + order["nombre_repas"] + "</td>", "utf-8"))
      self.wfile.write(bytes("<td>" + "nomrepas" + "</td>", "utf-8"))
      self.wfile.write(bytes("<td>" + order["horaireLivraison"] + " le " + order["dateLivraison"] + "</td>", "utf-8"))
      self.wfile.write(bytes("<td>" + str(order["prix_total"]) + "</td>", "utf-8"))
      self.wfile.write(bytes("</tr>", "utf-8"))

    self.wfile.write(bytes("</table>", "utf-8"))
    self.wfile.write(bytes("</body></html>", "utf-8"))


  def do_GET(self):
    if self.path == "/":
      self.getOrders()


server = HTTPServer((HOST, PORT), OrdersServer)
server.serve_forever()


