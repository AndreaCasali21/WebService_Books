using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using System.Windows.Documents;
using System.Windows.Input;
using System.Windows.Media;
using System.Windows.Media.Imaging;
using System.Windows.Navigation;
using System.Windows.Shapes;
using System.Net.Http;

namespace ServiceClient
{
    /// <summary>
    /// Logica di interazione per MainWindow.xaml
    /// </summary>
    public partial class MainWindow : Window
    {
        private int numcodice = 0;
        int id = 0;
        string codicecarrello = "";
        public MainWindow()
        {
           string t = "SELECT title FROM books WHERE arch_data BETWEEN "+"2018-03-11"+" AND "+"2019-20-01";
           string t1= "SELECT COUNT(books.ID) FROM books JOIN departments ON books.department = departments.ID JOIN bookcategories on books.ID = bookcategories.book WHERE departments.type = "+"fumetti"+" AND bookcategories.category = "+"Ultimi Arrivi";
           string t2 = " SELECT title, discount FROM `books` join bookcategories ON books.ID = bookcategories.book JOIN categories on bookcategories.category = categories.type  WHERE discount > 0 ORDER BY discount"; 

            InitializeComponent();
            btn_ricerca.IsEnabled = false;
            dp_data1.Visibility = System.Windows.Visibility.Hidden;
            dp_data2.Visibility = System.Windows.Visibility.Hidden;
            lbl_1.Visibility = System.Windows.Visibility.Hidden;
            lbl_2.Visibility = System.Windows.Visibility.Hidden;
            txt_codice.Visibility = System.Windows.Visibility.Hidden;
        }
        
        private void Btn_ricerca_Click(object sender, RoutedEventArgs e) {
            if (id==0)
            {
                string url = "http://10.13.100.25/work/webservices/books/queries.php?codice=" + numcodice;
                Getrequest(url);
            }
            else if(id==1)
            {              
                string url = "http://10.13.100.25/work/webservices/books/queries.php?codice=" + numcodice + "&date1=" + dp_data1.Text + "&date2=" + dp_data2.Text;
                Getrequest(url);
            }
            else
            {
                codicecarrello = txt_codice.Text;
                string url = "http://10.13.100.25/work/webservices/books/queries.php?codice=" + numcodice + "&idcarrello=" + codicecarrello;
                Getrequest(url);
            }
        }
        //Mysql
        /**
        private void Btn_ricerca_Click(object sender, RoutedEventArgs e)
        {
            if (id == 0)
            {
                string url = "http://10.13.100.25/work/webservices/books/queries.php?query=" + t;
                Getrequest(url);
            }
            else if (id == 1)
            {
                string url = "http://10.13.100.25/work/webservices/books/queries.php?query=" + t2;
                Getrequest(url);
            }
            else
            {
                codicecarrello = txt_codice.Text;
                string url = "http://10.13.100.25/work/webservices/books/queries.php?query=" + t3;
                Getrequest(url);
            }
        }*/

        /**
         * Inviatore della richiesta
         */
        async void Getrequest(string url)
        {
            using (HttpClient client = new HttpClient())
            {
                using (HttpResponseMessage response = await client.GetAsync(url))
                {
                    using (HttpContent content = response.Content)
                    {//possiamo usare HttpContentHeader headers = content.Headers;
                        string mycontent = await content.ReadAsStringAsync();                  
                        MessageBox.Show(mycontent);
                    }

                }

            }
        }        
        private void cmb_selezione_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            if(cmb_selezione.SelectedIndex == 0)
            {
                btn_ricerca.IsEnabled = true;
                numcodice = 1;
                dp_data1.Visibility = System.Windows.Visibility.Hidden;
                dp_data2.Visibility = System.Windows.Visibility.Hidden;
                lbl_1.Visibility = System.Windows.Visibility.Hidden;
                lbl_2.Visibility = System.Windows.Visibility.Hidden;
                txt_codice.Visibility = System.Windows.Visibility.Hidden;
                id = 0;
            }
            if (cmb_selezione.SelectedIndex == 1)
            {
                btn_ricerca.IsEnabled = true;
                numcodice = 2;
                dp_data1.Visibility = System.Windows.Visibility.Hidden;
                dp_data2.Visibility = System.Windows.Visibility.Hidden;
                lbl_1.Visibility = System.Windows.Visibility.Hidden;
                lbl_2.Visibility = System.Windows.Visibility.Hidden;
                txt_codice.Visibility = System.Windows.Visibility.Hidden;
                id = 0;
            }
            if (cmb_selezione.SelectedIndex == 2)
            {
                btn_ricerca.IsEnabled = true;
                numcodice = 3;
                dp_data1.Visibility = System.Windows.Visibility.Visible;
                dp_data2.Visibility = System.Windows.Visibility.Visible;
                txt_codice.Visibility = System.Windows.Visibility.Hidden;
                lbl_1.Visibility = System.Windows.Visibility.Visible;
                lbl_1.Content = "Inserisci la data iniziale";
                lbl_2.Visibility = System.Windows.Visibility.Visible;
                lbl_2.Content = "Inserisci la data finale";
                id = 1;
            }
            if (cmb_selezione.SelectedIndex == 3)
            {
                btn_ricerca.IsEnabled = true;
                numcodice = 4;
                dp_data1.Visibility = System.Windows.Visibility.Hidden;
                dp_data2.Visibility = System.Windows.Visibility.Hidden;
                lbl_1.Visibility = System.Windows.Visibility.Visible;
                lbl_1.Content = "Inserire il codice \rdel carrello";
                lbl_2.Visibility = System.Windows.Visibility.Hidden;
                txt_codice.Visibility = System.Windows.Visibility.Visible;               
                id = 2;
            }
        }
    }
}
