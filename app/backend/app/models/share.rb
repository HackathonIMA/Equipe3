class Share < ActiveRecord::Base
  belongs_to :school
  belongs_to :user

  enum category: {
    complain: 0,  # reclamação
    praise: 1,    # elogio
    notice: 2     # comunicado
  }

  def Share.from_today
    Model.where('extract(year  from date_column) = ? AND ', desired_year)
    Model.where('extract(month from date_column) = ?', desired_month)
    Model.where('extract(day   from date_column) = ?', desired_day_of_month)
    Time.at(x).to_date === Time.at(y).to_date
    Share.all
  end
end
